<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FixStudentClassNamesSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 IDENTIFICATION DES FICHIERS À CORRIGER CÔTÉ ÉTUDIANT...\n\n";
        
        $filesToFix = [
            // Vues étudiantes avec affichage de classe
            'resources/views/pages/student/grades/bulletin.blade.php' => [
                'line' => 57,
                'pattern' => '{{ $studentRecord->my_class->name ?? \'N/A\' }}',
                'replacement' => '{{ $studentRecord->my_class ? ($studentRecord->my_class->full_name ?: $studentRecord->my_class->name) : \'N/A\' }}'
            ],
            'resources/views/pages/student/progress/index.blade.php' => [
                'line' => 17,
                'pattern' => '{{ $sr->my_class->name }}',
                'replacement' => '{{ $sr->my_class->full_name ?: $sr->my_class->name }}'
            ],
            'resources/views/pages/student/finance/partials/receipt_details.blade.php' => [
                'line' => 69,
                'pattern' => '$studentRecord->my_class->name',
                'replacement' => '($studentRecord->my_class->full_name ?: $studentRecord->my_class->name)'
            ],
            'resources/views/pages/student/finance/receipt_pdf.blade.php' => [
                'line' => 136,
                'pattern' => '{{ $receipt->paymentRecord->student->student_record->my_class->name ?? \'N/A\' }}',
                'replacement' => '{{ $receipt->paymentRecord->student->student_record->my_class ? ($receipt->paymentRecord->student->student_record->my_class->full_name ?: $receipt->paymentRecord->student->student_record->my_class->name) : \'N/A\' }}'
            ],
            'resources/views/pages/student/finance/receipts_print.blade.php' => [
                'line' => 118,
                'pattern' => '{{ $student->class->name ?? \'N/A\' }}',
                'replacement' => '{{ $student->class ? ($student->class->full_name ?: $student->class->name) : \'N/A\' }}'
            ]
        ];
        
        echo "📋 FICHIERS IDENTIFIÉS POUR CORRECTION:\n";
        foreach ($filesToFix as $file => $info) {
            echo "   ├─ {$file}\n";
            echo "   │  ├─ Ligne: {$info['line']}\n";
            echo "   │  ├─ Actuel: {$info['pattern']}\n";
            echo "   │  └─ Nouveau: {$info['replacement']}\n";
            echo "\n";
        }
        
        echo "🎯 CONTRÔLEURS À VÉRIFIER:\n";
        $controllersToCheck = [
            'app/Http/Controllers/Student/FinanceController.php',
            'app/Http/Controllers/Student/MyGradesController.php',
            'app/Http/Controllers/Student/ProgressController.php',
            'app/Http/Controllers/Student/DashboardController.php',
            'app/Http/Controllers/Student/TimetableController.php'
        ];
        
        foreach ($controllersToCheck as $controller) {
            echo "   ├─ {$controller}\n";
        }
        
        echo "\n✅ STRATÉGIE DE CORRECTION:\n";
        echo "   ├─ 1. Modifier les vues pour utiliser full_name\n";
        echo "   ├─ 2. Vérifier les contrôleurs chargent les relations\n";
        echo "   ├─ 3. Tester chaque page côté étudiant\n";
        echo "   └─ 4. S'assurer de la cohérence partout\n";
        
        echo "\n🚀 ZONES D'IMPACT:\n";
        echo "   ├─ Bulletin de notes: Nom complet de classe\n";
        echo "   ├─ Reçus de paiement: Nom complet de classe\n";
        echo "   ├─ PDF de reçus: Nom complet de classe\n";
        echo "   ├─ Tableau de bord: Nom complet de classe\n";
        echo "   ├─ Emploi du temps: Nom complet de classe\n";
        echo "   └─ Toutes les pages étudiantes\n";
    }
}
