<?php

namespace App\Imports;

use App\Models\Mark;
use App\Models\User;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Helpers\Qs;
use App\Helpers\PeriodCalculator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MarksImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $exam_id;
    protected $class_id;
    protected $section_id;
    protected $year;

    public function __construct($exam_id, $class_id, $section_id)
    {
        $this->exam_id = $exam_id;
        $this->class_id = $class_id;
        $this->section_id = $section_id;
        $this->year = Qs::getSetting('current_session');
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            // Rechercher l'étudiant par nom ou numéro matricule
            $student = $this->findStudent($row);
            
            if (!$student) {
                continue; // Ignorer si l'étudiant n'est pas trouvé
            }

            // Rechercher la matière
            $subject = $this->findSubject($row);
            
            if (!$subject) {
                continue; // Ignorer si la matière n'est pas trouvée
            }

            // Créer ou mettre à jour la note
            $mark_data = [
                'student_id' => $student->user_id,
                'exam_id' => $this->exam_id,
                'my_class_id' => $this->class_id,
                'section_id' => $this->section_id,
                'subject_id' => $subject->id,
                'year' => $this->year,
            ];

            $mark = Mark::firstOrCreate($mark_data);

            // Mettre à jour les notes selon le système RDC
            $update_data = [];
            
            // Notes des périodes (devoirs/interrogations)
            if (isset($row['periode_1']) && is_numeric($row['periode_1'])) {
                $update_data['p1_avg'] = $this->normalizeGrade($row['periode_1']);
            }
            if (isset($row['periode_2']) && is_numeric($row['periode_2'])) {
                $update_data['p2_avg'] = $this->normalizeGrade($row['periode_2']);
            }
            if (isset($row['periode_3']) && is_numeric($row['periode_3'])) {
                $update_data['p3_avg'] = $this->normalizeGrade($row['periode_3']);
            }
            if (isset($row['periode_4']) && is_numeric($row['periode_4'])) {
                $update_data['p4_avg'] = $this->normalizeGrade($row['periode_4']);
            }

            // Notes d'examens semestriels
            if (isset($row['examen_semestre_1']) && is_numeric($row['examen_semestre_1'])) {
                $update_data['s1_exam'] = $this->normalizeGrade($row['examen_semestre_1']);
            }
            if (isset($row['examen_semestre_2']) && is_numeric($row['examen_semestre_2'])) {
                $update_data['s2_exam'] = $this->normalizeGrade($row['examen_semestre_2']);
            }

            // Anciennes colonnes pour compatibilité
            if (isset($row['test_1']) && is_numeric($row['test_1'])) {
                $update_data['t1'] = $this->normalizeGrade($row['test_1']);
            }
            if (isset($row['test_2']) && is_numeric($row['test_2'])) {
                $update_data['t2'] = $this->normalizeGrade($row['test_2']);
            }
            if (isset($row['examen']) && is_numeric($row['examen'])) {
                $update_data['exm'] = $this->normalizeGrade($row['examen']);
            }

            // Calculer TCA (Total Contrôle Continu)
            if (isset($update_data['t1']) && isset($update_data['t2'])) {
                $update_data['tca'] = $update_data['t1'] + $update_data['t2'];
            }

            // Calculer total semestre
            $exam = \App\Models\Exam::find($this->exam_id);
            if ($exam) {
                $tex_field = 'tex' . $exam->semester;
                if (isset($update_data['tca']) && isset($update_data['exm'])) {
                    $total = $update_data['tca'] + $update_data['exm'];
                    if ($total <= 100) { // Validation du total
                        $update_data[$tex_field] = $total;
                    }
                }
            }

            // Mettre à jour la note
            if (!empty($update_data)) {
                $mark->update($update_data);

                // Recalculer automatiquement les moyennes de périodes si applicable
                if (class_exists('App\Helpers\PeriodCalculator')) {
                    try {
                        PeriodCalculator::updateAllPeriodAveragesForStudent($student->user_id, $this->class_id, $subject->id);
                    } catch (\Exception $e) {
                        \Log::warning('Erreur recalcul moyennes périodes: ' . $e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            '*.nom_etudiant' => 'required|string',
            '*.matiere' => 'required|string',
            '*.periode_1' => 'nullable|numeric|min:0|max:20',
            '*.periode_2' => 'nullable|numeric|min:0|max:20',
            '*.periode_3' => 'nullable|numeric|min:0|max:20',
            '*.periode_4' => 'nullable|numeric|min:0|max:20',
            '*.examen_semestre_1' => 'nullable|numeric|min:0|max:20',
            '*.examen_semestre_2' => 'nullable|numeric|min:0|max:20',
            '*.test_1' => 'nullable|numeric|min:0|max:100',
            '*.test_2' => 'nullable|numeric|min:0|max:100',
            '*.examen' => 'nullable|numeric|min:0|max:100',
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function customValidationMessages()
    {
        return [
            '*.nom_etudiant.required' => 'Le nom de l\'étudiant est obligatoire',
            '*.matiere.required' => 'La matière est obligatoire',
            '*.periode_1.numeric' => 'La note de période 1 doit être un nombre',
            '*.periode_1.max' => 'La note de période 1 ne peut pas dépasser 20',
            '*.examen_semestre_1.max' => 'La note d\'examen ne peut pas dépasser 20',
        ];
    }

    /**
     * Rechercher un étudiant par nom ou numéro matricule
     */
    private function findStudent($row)
    {
        $student = null;

        // Recherche par numéro matricule d'abord
        if (isset($row['numero_matricule']) && !empty($row['numero_matricule'])) {
            $student = StudentRecord::where('adm_no', $row['numero_matricule'])
                ->where('my_class_id', $this->class_id)
                ->where('section_id', $this->section_id)
                ->where('session', $this->year)
                ->first();
        }

        // Recherche par nom si pas trouvé
        if (!$student && isset($row['nom_etudiant']) && !empty($row['nom_etudiant'])) {
            $student = StudentRecord::whereHas('user', function($q) use ($row) {
                $q->where('name', 'LIKE', '%' . trim($row['nom_etudiant']) . '%');
            })
            ->where('my_class_id', $this->class_id)
            ->where('section_id', $this->section_id)
            ->where('session', $this->year)
            ->first();
        }

        return $student;
    }

    /**
     * Rechercher une matière par nom
     */
    private function findSubject($row)
    {
        if (!isset($row['matiere']) || empty($row['matiere'])) {
            return null;
        }

        $subject_name = trim($row['matiere']);
        
        return Subject::where('name', 'LIKE', '%' . $subject_name . '%')
            ->orWhere('slug', 'LIKE', '%' . $subject_name . '%')
            ->first();
    }

    /**
     * Normaliser une note (convertir sur 20 si nécessaire)
     */
    private function normalizeGrade($grade)
    {
        $grade = floatval($grade);
        
        // Si la note semble être sur 100, la convertir sur 20
        if ($grade > 20) {
            $grade = ($grade / 100) * 20;
        }
        
        // S'assurer que la note est dans la plage 0-20
        return max(0, min(20, $grade));
    }
}
