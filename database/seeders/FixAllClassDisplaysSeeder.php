<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FixAllClassDisplaysSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 IDENTIFICATION DE TOUS LES AFFICHAGES DE CLASSE À CORRIGER...\n\n";
        
        $filesToFix = [
            // Support Team - Sections
            'pages/support_team/sections/edit.blade.php' => [
                'patterns' => [
                    '$s->my_class->name' => '$s->my_class ? ($s->my_class->full_name ?: $s->my_class->name) : \'N/A\''
                ]
            ],
            'pages/support_team/sections/index.blade.php' => [
                'patterns' => [
                    '$s->my_class->name' => '$s->my_class ? ($s->my_class->full_name ?: $s->my_class->name) : \'N/A\''
                ]
            ],
            
            // Support Team - Timetables
            'pages/support_team/timetables/index.blade.php' => [
                'patterns' => [
                    '$ttr->my_class->name' => '$ttr->my_class ? ($ttr->my_class->full_name ?: $ttr->my_class->name) : \'N/A\''
                ]
            ],
            'pages/support_team/timetables/show.blade.php' => [
                'patterns' => [
                    '$my_class->name' => '$my_class ? ($my_class->full_name ?: $my_class->name) : \'N/A\''
                ]
            ],
            'pages/support_team/timetables/manage.blade.php' => [
                'patterns' => [
                    '$my_class->name' => '$my_class ? ($my_class->full_name ?: $my_class->name) : \'N/A\''
                ]
            ],
            'pages/support_team/timetables/print.blade.php' => [
                'patterns' => [
                    '$my_class->name' => '$my_class ? ($my_class->full_name ?: $my_class->name) : \'N/A\''
                ]
            ],
            
            // Support Team - Students
            'pages/support_team/students/show.blade.php' => [
                'patterns' => [
                    '$sr->my_class->name' => '$sr->my_class ? ($sr->my_class->full_name ?: $sr->my_class->name) : \'N/A\''
                ]
            ],
            'pages/support_team/students/list.blade.php' => [
                'patterns' => [
                    '$my_class->name' => '$my_class ? ($my_class->full_name ?: $my_class->name) : \'N/A\''
                ]
            ],
            'pages/support_team/students/graduated.blade.php' => [
                'patterns' => [
                    '$s->my_class->name' => '$s->my_class ? ($s->my_class->full_name ?: $s->my_class->name) : \'N/A\''
                ]
            ],
            'pages/support_team/students/statistics.blade.php' => [
                'patterns' => [
                    '$class->name' => '$class ? ($class->full_name ?: $class->name) : \'N/A\'',
                    '$section->my_class->name' => '$section->my_class ? ($section->my_class->full_name ?: $section->my_class->name) : \'N/A\''
                ]
            ],
            
            // Support Team - Users
            'pages/support_team/users/show.blade.php' => [
                'patterns' => [
                    '$sr->my_class->name' => '$sr->my_class ? ($sr->my_class->full_name ?: $sr->my_class->name) : \'N/A\'',
                    '$sub->my_class->name' => '$sub->my_class ? ($sub->my_class->full_name ?: $sub->my_class->name) : \'N/A\''
                ]
            ],
            
            // Support Team - Marks
            'pages/support_team/marks/manage.blade.php' => [
                'patterns' => [
                    '$m->my_class->name' => '$m->my_class ? ($m->my_class->full_name ?: $m->my_class->name) : \'N/A\''
                ]
            ],
            
            // Support Team - Study Materials
            'pages/support_team/study_materials/index.blade.php' => [
                'patterns' => [
                    '$class->name' => '$class ? ($class->full_name ?: $class->name) : \'N/A\''
                ]
            ],
            'pages/support_team/study_materials/create.blade.php' => [
                'patterns' => [
                    '$class->name' => '$class ? ($class->full_name ?: $class->name) : \'N/A\''
                ]
            ],
            'pages/support_team/study_materials/edit.blade.php' => [
                'patterns' => [
                    '$class->name' => '$class ? ($class->full_name ?: $class->name) : \'N/A\''
                ]
            ]
        ];
        
        echo "📋 FICHIERS IDENTIFIÉS POUR CORRECTION:\n";
        foreach ($filesToFix as $file => $info) {
            echo "   ├─ {$file}\n";
            foreach ($info['patterns'] as $old => $new) {
                echo "   │  ├─ {$old} → {$new}\n";
            }
            echo "   │\n";
        }
        
        echo "\n🎯 CONTRÔLEURS À VÉRIFIER:\n";
        $controllersToCheck = [
            'SupportTeam/SubjectController.php' => '✅ Déjà corrigé',
            'SupportTeam/StudentRecordController.php' => '✅ Déjà corrigé', 
            'SupportTeam/SectionController.php' => '❓ À vérifier',
            'SupportTeam/TimeTableController.php' => '❓ À vérifier',
            'SupportTeam/MarkController.php' => '❓ À vérifier',
            'SupportTeam/StudyMaterialController.php' => '❓ À vérifier',
            'Teacher/*' => '❓ À vérifier tous',
            'Accountant/*' => '❓ À vérifier tous',
            'Librarian/*' => '❓ À vérifier tous'
        ];
        
        foreach ($controllersToCheck as $controller => $status) {
            echo "   ├─ {$controller} → {$status}\n";
        }
        
        echo "\n🚀 ZONES D'IMPACT:\n";
        echo "   ├─ 📚 Gestion des matières: Noms complets partout\n";
        echo "   ├─ 👥 Gestion des sections: Noms complets partout\n";
        echo "   ├─ 📅 Emplois du temps: Noms complets partout\n";
        echo "   ├─ 🎓 Profils étudiants: Noms complets partout\n";
        echo "   ├─ 📊 Statistiques: Noms complets partout\n";
        echo "   ├─ 📝 Gestion des notes: Noms complets partout\n";
        echo "   ├─ 📖 Supports pédagogiques: Noms complets partout\n";
        echo "   ├─ 👨‍🏫 Interface enseignant: À corriger\n";
        echo "   ├─ 💰 Interface comptable: À corriger\n";
        echo "   └─ 📚 Interface bibliothécaire: À corriger\n";
        
        echo "\n✅ STRATÉGIE:\n";
        echo "   ├─ 1. Corriger toutes les vues Support Team\n";
        echo "   ├─ 2. Vérifier et corriger les contrôleurs\n";
        echo "   ├─ 3. Corriger les interfaces Teacher/Accountant/Librarian\n";
        echo "   ├─ 4. Tester chaque rôle utilisateur\n";
        echo "   └─ 5. S'assurer de la cohérence totale\n";
    }
}
