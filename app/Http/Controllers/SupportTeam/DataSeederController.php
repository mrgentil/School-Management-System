<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentRecord;
use App\Models\Mark;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SubjectGradeConfig;
use App\Models\Exam;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DataSeederController extends Controller
{
    protected $year;
    protected $logs = [];
    
    // Prénoms congolais
    protected $firstNames = [
        'male' => ['Jean', 'Pierre', 'Paul', 'Joseph', 'Emmanuel', 'David', 'Samuel', 'Daniel', 'Patrick', 'Christian', 
                   'Blaise', 'Cédric', 'Fiston', 'Gloire', 'Héritier', 'Junior', 'Kevin', 'Luc', 'Marc', 'Nathan'],
        'female' => ['Marie', 'Grace', 'Esther', 'Ruth', 'Sarah', 'Rebecca', 'Rachel', 'Deborah', 'Naomi', 'Judith',
                     'Béatrice', 'Carole', 'Divine', 'Élodie', 'Fleur', 'Gracia', 'Henriette', 'Irène', 'Joséphine', 'Kelly']
    ];
    
    // Noms de famille congolais
    protected $lastNames = [
        'Mbala', 'Tshimanga', 'Kabongo', 'Mutombo', 'Kalala', 'Kasongo', 'Mbuyi', 'Nkulu', 'Ilunga', 'Ngoy',
        'Kapinga', 'Mwamba', 'Bakala', 'Mukendi', 'Tshilombo', 'Kalonji', 'Mwenze', 'Kabamba', 'Ntumba', 'Kabuya',
        'Lumumba', 'Katumbi', 'Kayembe', 'Bukasa', 'Lubamba', 'Ngandu', 'Kazadi', 'Tshibanda', 'Mujinga', 'Kalombo'
    ];

    public function index()
    {
        $classes = MyClass::with('section')->get();
        $students = User::where('user_type', 'student')->count();
        $marks = Mark::count();
        $assignments = Assignment::count();
        
        return view('pages.support_team.seeder.index', compact('classes', 'students', 'marks', 'assignments'));
    }

    public function seed()
    {
        $this->year = Qs::getSetting('current_session') ?: '2024-2025';
        
        DB::beginTransaction();
        
        try {
            $classes = MyClass::with('section')->get();
            
            if ($classes->isEmpty()) {
                return redirect()->route('seeder.index')->with('flash_danger', 'Aucune classe trouvée. Veuillez d\'abord créer des classes.');
            }
            
            $this->log("Année scolaire: {$this->year}");
            $this->log("Classes trouvées: " . $classes->count());
            
            // Créer un enseignant
            $teacher = $this->createTeacher();
            
            // Créer les examens une seule fois
            $exam1 = Exam::firstOrCreate(
                ['semester' => 1, 'year' => $this->year],
                ['name' => 'Examen Semestriel 1', 'status' => 'completed']
            );
            
            $exam2 = Exam::firstOrCreate(
                ['semester' => 2, 'year' => $this->year],
                ['name' => 'Examen Semestriel 2', 'status' => 'active']
            );
            $this->log("Examens créés: S1 et S2");

            foreach ($classes as $class) {
                $this->log("=== Classe: {$class->name} ===");
                
                // Section par défaut
                $section = $class->section->first();
                if (!$section) {
                    $section = Section::create(['name' => 'A', 'my_class_id' => $class->id]);
                    $this->log("Section créée: A");
                }
                
                // Créer matières
                $subjects = $this->createSubjects($class, $teacher);
                
                // Créer configs de notes
                $this->createGradeConfigs($class, $subjects);
                
                // Créer 12 étudiants
                $students = $this->createStudents($class, $section, 12);
                
                // Créer devoirs
                $assignments = $this->createAssignments($class, $section, $subjects, $teacher);
                
                // Créer soumissions
                $this->createSubmissions($assignments, $students);
                
                // Créer notes (t1-t4, tca) avec exam_id
                $this->createMarks($class, $section, $subjects, $students, $exam1);
                
                // Ajouter notes d'examens
                $this->addExamGrades($class, $subjects, $students, $exam1, $exam2);
            }
            
            DB::commit();
            
            $summary = $this->getSummary();
            
            return redirect()->route('seeder.index')->with('flash_success', 
                "Données créées avec succès!\n" . implode("\n", $this->logs) . "\n\n" . $summary
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('seeder.index')->with('flash_danger', 'Erreur: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
    
    protected function log($message)
    {
        $this->logs[] = $message;
    }
    
    protected function createTeacher()
    {
        $teacher = User::where('user_type', 'teacher')->first();
        
        if (!$teacher) {
            $teacher = User::create([
                'name' => 'Prof. Jean-Marie Kabongo',
                'email' => 'prof.kabongo@eschool.cd',
                'username' => 'prof_kabongo',
                'password' => Hash::make('password123'),
                'user_type' => 'teacher',
                'gender' => 'Male',
                'code' => 'TCH' . rand(1000, 9999)
            ]);
            $this->log("Enseignant créé: {$teacher->name}");
        }
        
        return $teacher;
    }
    
    protected function createSubjects($class, $teacher)
    {
        $subjectNames = ['Mathématiques', 'Français', 'Anglais', 'Physique', 'Chimie', 'Informatique', 'Histoire', 'Géographie'];
        $subjects = [];
        
        foreach ($subjectNames as $name) {
            $subject = Subject::firstOrCreate(
                ['my_class_id' => $class->id, 'name' => $name],
                ['slug' => Str::slug($name), 'teacher_id' => $teacher->id]
            );
            $subjects[] = $subject;
        }
        
        $this->log("Matières: " . count($subjects));
        return $subjects;
    }
    
    protected function createGradeConfigs($class, $subjects)
    {
        foreach ($subjects as $subject) {
            $periodMax = in_array($subject->name, ['Mathématiques', 'Français']) ? 40 : 20;
            $examMax = in_array($subject->name, ['Mathématiques', 'Français']) ? 80 : 40;
            
            SubjectGradeConfig::updateOrCreate(
                ['my_class_id' => $class->id, 'subject_id' => $subject->id, 'academic_year' => $this->year],
                ['period_max_points' => $periodMax, 'exam_max_points' => $examMax, 'active' => true]
            );
        }
        $this->log("Configs notes: " . count($subjects));
    }
    
    protected function createStudents($class, $section, $count)
    {
        $students = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $gender = rand(0, 1) ? 'Male' : 'Female';
            $genderKey = $gender === 'Male' ? 'male' : 'female';
            
            $firstName = $this->firstNames[$genderKey][array_rand($this->firstNames[$genderKey])];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            
            $email = strtolower(Str::slug($firstName)) . '.' . strtolower($lastName) . $class->id . $i . '@student.cd';
            
            // Vérifier si existe déjà
            $existing = User::where('email', $email)->first();
            if ($existing) {
                $students[] = $existing;
                continue;
            }
            
            $admNo = 'STU' . $class->id . str_pad($i, 3, '0', STR_PAD_LEFT);
            
            $user = User::create([
                'name' => "$firstName $lastName",
                'email' => $email,
                'username' => strtolower($firstName) . '_' . strtolower($lastName) . $class->id . $i,
                'password' => Hash::make('password123'),
                'user_type' => 'student',
                'gender' => $gender,
                'code' => $admNo,
                'dob' => now()->subYears(rand(15, 19))->format('Y-m-d')
            ]);
            
            StudentRecord::create([
                'user_id' => $user->id,
                'my_class_id' => $class->id,
                'section_id' => $section->id,
                'session' => $this->year,
                'adm_no' => $admNo,
                'year_admitted' => now()->year
            ]);
            
            $students[] = $user;
        }
        
        $this->log("Étudiants: " . count($students));
        return $students;
    }
    
    protected function createAssignments($class, $section, $subjects, $teacher)
    {
        $assignments = [];
        
        foreach ([1, 2, 3, 4] as $period) {
            foreach ($subjects as $subject) {
                for ($i = 1; $i <= 2; $i++) {
                    $assignment = Assignment::firstOrCreate(
                        [
                            'my_class_id' => $class->id,
                            'subject_id' => $subject->id,
                            'period' => $period,
                            'title' => "Devoir $i - {$subject->name} P$period"
                        ],
                        [
                            'section_id' => $section->id,
                            'teacher_id' => $teacher->id,
                            'description' => "Devoir de {$subject->name}",
                            'due_date' => now()->addDays(rand(-20, 20)),
                            'max_score' => rand(0, 1) ? 20 : 10,
                            'status' => 'active',
                            'year' => $this->year
                        ]
                    );
                    $assignments[] = $assignment;
                }
            }
        }
        
        $this->log("Devoirs: " . count($assignments));
        return $assignments;
    }
    
    protected function createSubmissions($assignments, $students)
    {
        $count = 0;
        
        foreach ($assignments as $assignment) {
            foreach ($students as $student) {
                if (rand(1, 100) <= 85) { // 85% soumettent
                    $score = $this->randomScore($assignment->max_score);
                    
                    AssignmentSubmission::firstOrCreate(
                        ['assignment_id' => $assignment->id, 'student_id' => $student->id],
                        [
                            'submission_text' => 'Travail soumis',
                            'submitted_at' => now()->subDays(rand(1, 10)),
                            'score' => $score,
                            'status' => 'graded'
                        ]
                    );
                    $count++;
                }
            }
        }
        
        $this->log("Soumissions: $count");
    }
    
    protected function createMarks($class, $section, $subjects, $students, $exam)
    {
        $count = 0;
        
        foreach ($subjects as $subject) {
            $config = SubjectGradeConfig::getConfig($class->id, $subject->id, $this->year);
            $maxPoints = $config ? $config->period_max_points : 20;
            
            foreach ($students as $student) {
                $level = rand(45, 95) / 100; // Niveau étudiant
                
                $mark = Mark::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'my_class_id' => $class->id,
                        'year' => $this->year
                    ],
                    [
                        'section_id' => $section->id,
                        'exam_id' => $exam->id
                    ]
                );
                
                $mark->t1 = round($this->studentScore($maxPoints, $level), 2);
                $mark->t2 = round($this->studentScore($maxPoints, $level), 2);
                $mark->t3 = round($this->studentScore($maxPoints, $level), 2);
                $mark->t4 = round($this->studentScore($maxPoints, $level), 2);
                $mark->tca = round(($mark->t1 + $mark->t2 + $mark->t3 + $mark->t4) / 4, 2);
                $mark->save();
                
                $count++;
            }
        }
        
        $this->log("Notes: $count");
    }
    
    protected function addExamGrades($class, $subjects, $students, $exam1, $exam2)
    {
        foreach ($subjects as $subject) {
            $config = SubjectGradeConfig::getConfig($class->id, $subject->id, $this->year);
            $maxExam = $config ? $config->exam_max_points : 40;
            $pMax = $config ? $config->period_max_points : 20;
            
            foreach ($students as $student) {
                $mark = Mark::where([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'my_class_id' => $class->id,
                    'year' => $this->year
                ])->first();
                
                if ($mark) {
                    $avgP = ($mark->t1 + $mark->t2) / 2;
                    $level = $pMax > 0 ? $avgP / $pMax : 0.5;
                    
                    $mark->s1_exam = round($this->studentScore($maxExam, $level), 2);
                    $mark->exm = $mark->s1_exam;
                    $mark->save();
                }
            }
        }
        
        $this->log("Notes d'examens ajoutées");
    }
    
    protected function randomScore($max)
    {
        $percentage = rand(40, 95) / 100;
        return round($percentage * $max, 2);
    }
    
    protected function studentScore($max, $level)
    {
        $variation = rand(-10, 10) / 100;
        $score = max(0.3, min(1, $level + $variation));
        return round($score * $max, 2);
    }
    
    protected function getSummary()
    {
        return "RÉSUMÉ:\n" .
               "- Classes: " . MyClass::count() . "\n" .
               "- Étudiants: " . User::where('user_type', 'student')->count() . "\n" .
               "- Matières: " . Subject::count() . "\n" .
               "- Devoirs: " . Assignment::count() . "\n" .
               "- Soumissions: " . AssignmentSubmission::count() . "\n" .
               "- Notes: " . Mark::count() . "\n" .
               "- Examens: " . Exam::count();
    }
}
