<?php

namespace App\Exports;

use App\Models\ExamRecord;
use App\Models\Mark;
use App\Models\Exam;
use App\Models\MyClass;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExamResultsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $exam_id;
    protected $class_id;
    protected $exam;
    protected $my_class;

    public function __construct($exam_id, $class_id)
    {
        $this->exam_id = $exam_id;
        $this->class_id = $class_id;
        $this->exam = Exam::find($exam_id);
        $this->my_class = MyClass::find($class_id);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ExamRecord::where('exam_id', $this->exam_id)
            ->where('my_class_id', $this->class_id)
            ->with(['student.user', 'student.section'])
            ->orderBy('pos')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Position',
            'Nom de l\'Étudiant',
            'Numéro Matricule',
            'Section',
            'Moyenne Générale (%)',
            'Total Points',
            'Moyenne de Classe',
            'Statut',
            'Mention',
            'Date d\'Examen'
        ];
    }

    /**
     * @param mixed $record
     * @return array
     */
    public function map($record): array
    {
        return [
            $record->pos ?? 'N/A',
            $record->student->user->name ?? 'N/A',
            $record->student->adm_no ?? 'N/A',
            $record->student->section->name ?? 'N/A',
            round($record->ave, 2) . '%',
            $record->total ?? 0,
            round($record->class_ave, 2) . '%',
            $this->getStatus($record->ave),
            $this->getMention($record->ave),
            $record->created_at->format('d/m/Y')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour l'en-tête
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '366092'],
                ],
            ],
            // Style pour les données
            'A:J' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    private function getStatus($average)
    {
        if ($average >= 50) {
            return 'ADMIS';
        } else {
            return 'ÉCHEC';
        }
    }

    private function getMention($average)
    {
        if ($average >= 90) {
            return 'EXCELLENT';
        } elseif ($average >= 80) {
            return 'TRÈS BIEN';
        } elseif ($average >= 70) {
            return 'BIEN';
        } elseif ($average >= 60) {
            return 'ASSEZ BIEN';
        } elseif ($average >= 50) {
            return 'PASSABLE';
        } else {
            return 'INSUFFISANT';
        }
    }
}
