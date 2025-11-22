<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Repositories\StudentRepo;
use App\Helpers\Qs;

class DiagnoseMarksRecordNotFoundSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 DIAGNOSTIC DE L'ERREUR 'ENREGISTREMENT NON TROUVÉ'...\n\n";
        
        echo "🎯 PROBLÈME IDENTIFIÉ:\n";
        echo "   ├─ Section cachée → JavaScript ne remplit pas correctement\n";
        echo "   ├─ Contrôleur cherche étudiants avec section_id vide\n";
        echo "   ├─ Aucun étudiant trouvé → Erreur 'enregistrement non trouvé'\n";
        echo "   └─ Besoin de logique de fallback automatique\n\n";
        
        // Vérifier les relations classe-section
        echo "📊 VÉRIFICATION DES RELATIONS CLASSE-SECTION:\n";
        
        $classes = MyClass::with('section')->take(5)->get();
        foreach ($classes as $class) {
            echo "   ├─ Classe: {$class->name} (ID: {$class->id})\n";
            
            if ($class->section && $class->section->count() > 0) {
                echo "   │  ├─ Sections disponibles: " . $class->section->count() . "\n";
                foreach ($class->section as $section) {
                    echo "   │  │  ├─ {$section->name} (ID: {$section->id})\n";
                    
                    // Vérifier les étudiants dans cette section
                    $students = StudentRecord::where('my_class_id', $class->id)
                        ->where('section_id', $section->id)
                        ->where('session', Qs::getSetting('current_session'))
                        ->count();
                    echo "   │  │  └─ Étudiants: {$students}\n";
                }
                
                $firstSection = $class->section->first();
                echo "   │  └─ Section auto-sélectionnée: {$firstSection->name} (ID: {$firstSection->id})\n";
            } else {
                echo "   │  └─ ❌ PROBLÈME: Aucune section trouvée!\n";
            }
            echo "\n";
        }
        
        // Test de la logique du contrôleur
        echo "🔧 TEST DE LA LOGIQUE DU CONTRÔLEUR:\n";
        
        $testClassId = 40; // Classe de l'exemple
        $testClass = MyClass::find($testClassId);
        
        if ($testClass) {
            echo "   ├─ Classe de test: {$testClass->name} (ID: {$testClassId})\n";
            
            $sections = $testClass->section;
            if ($sections && $sections->count() > 0) {
                $firstSection = $sections->first();
                echo "   ├─ Première section: {$firstSection->name} (ID: {$firstSection->id})\n";
                
                // Test de recherche d'étudiants
                $studentRepo = new StudentRepo();
                $searchData = [
                    'my_class_id' => $testClassId,
                    'section_id' => $firstSection->id,
                    'session' => Qs::getSetting('current_session')
                ];
                
                echo "   ├─ Recherche d'étudiants avec:\n";
                echo "   │  ├─ my_class_id: {$searchData['my_class_id']}\n";
                echo "   │  ├─ section_id: {$searchData['section_id']}\n";
                echo "   │  └─ session: {$searchData['session']}\n";
                
                $students = $studentRepo->getRecord($searchData)->get();
                echo "   ├─ Étudiants trouvés: " . $students->count() . "\n";
                
                if ($students->count() > 0) {
                    echo "   ├─ ✅ SOLUTION: La logique fonctionne!\n";
                    echo "   └─ Premier étudiant: " . $students->first()->user->name . "\n";
                } else {
                    echo "   ├─ ❌ PROBLÈME: Aucun étudiant trouvé!\n";
                    echo "   └─ 🔍 Vérification alternative...\n";
                    
                    // Recherche alternative sans section spécifique
                    $allStudents = StudentRecord::where('my_class_id', $testClassId)
                        ->where('session', Qs::getSetting('current_session'))
                        ->with('user')
                        ->get();
                    
                    echo "      ├─ Étudiants dans la classe (toutes sections): " . $allStudents->count() . "\n";
                    
                    if ($allStudents->count() > 0) {
                        $sectionsUsed = $allStudents->pluck('section_id')->unique();
                        echo "      ├─ Sections utilisées: " . $sectionsUsed->implode(', ') . "\n";
                        echo "      └─ 💡 Solution: Utiliser la première section avec des étudiants\n";
                    }
                }
            } else {
                echo "   └─ ❌ PROBLÈME: Aucune section dans cette classe!\n";
            }
        } else {
            echo "   └─ ❌ PROBLÈME: Classe {$testClassId} non trouvée!\n";
        }
        echo "\n";
        
        echo "🔧 SOLUTIONS IMPLÉMENTÉES:\n";
        echo "   ├─ 1️⃣ Contrôleur modifié:\n";
        echo "   │  ├─ Détection de section_id vide\n";
        echo "   │  ├─ Auto-sélection première section de la classe\n";
        echo "   │  └─ Fallback intelligent\n";
        echo "   ├─ 2️⃣ JavaScript amélioré:\n";
        echo "   │  ├─ Logs de débogage ajoutés\n";
        echo "   │  ├─ Vérification de la sélection\n";
        echo "   │  └─ Remplissage automatique du champ caché\n";
        echo "   └─ 3️⃣ Validation adaptée:\n";
        echo "      ├─ section_id devient 'nullable'\n";
        echo "      └─ Plus d'erreur de validation\n\n";
        
        echo "🚀 WORKFLOW CORRIGÉ:\n";
        echo "   ├─ 1️⃣ Utilisateur sélectionne classe\n";
        echo "   ├─ 2️⃣ JavaScript trouve première section\n";
        echo "   ├─ 3️⃣ Champ caché rempli automatiquement\n";
        echo "   ├─ 4️⃣ Soumission du formulaire\n";
        echo "   ├─ 5️⃣ Contrôleur vérifie section_id\n";
        echo "   ├─ 6️⃣ Si vide → Auto-sélection première section\n";
        echo "   ├─ 7️⃣ Recherche étudiants avec section valide\n";
        echo "   └─ 8️⃣ Redirection vers gestion des notes\n\n";
        
        echo "🌐 TESTER LA CORRECTION:\n";
        echo "   ├─ 🌐 URL: http://localhost:8000/marks\n";
        echo "   ├─ 1️⃣ Ouvrir la console du navigateur (F12)\n";
        echo "   ├─ 2️⃣ Sélectionner un examen\n";
        echo "   ├─ 3️⃣ Sélectionner une classe\n";
        echo "   ├─ 4️⃣ Vérifier les logs dans la console\n";
        echo "   ├─ 5️⃣ Sélectionner une matière\n";
        echo "   ├─ 6️⃣ Cliquer sur 'Continuer'\n";
        echo "   └─ 7️⃣ Vérifier que ça fonctionne maintenant\n\n";
        
        echo "💡 POINTS DE VÉRIFICATION:\n";
        echo "   ├─ 🔍 Console navigateur: Logs de sélection section\n";
        echo "   ├─ 📝 Champ caché: Valeur section_id remplie\n";
        echo "   ├─ 🎯 Contrôleur: Fallback automatique\n";
        echo "   ├─ 👥 Étudiants: Trouvés dans la section\n";
        echo "   └─ ✅ Redirection: Vers page de gestion des notes\n\n";
        
        echo "🔧 SI LE PROBLÈME PERSISTE:\n";
        echo "   ├─ 1️⃣ Vérifier que la classe a des sections\n";
        echo "   ├─ 2️⃣ Vérifier que les sections ont des étudiants\n";
        echo "   ├─ 3️⃣ Vérifier l'année académique courante\n";
        echo "   ├─ 4️⃣ Vérifier les logs de la console\n";
        echo "   └─ 5️⃣ Tester avec une autre classe\n\n";
        
        echo "🎉 RÉSULTAT ATTENDU:\n";
        echo "   ✅ Plus d'erreur 'enregistrement non trouvé'\n";
        echo "   ✅ Sélection automatique intelligente\n";
        echo "   ✅ Interface simplifiée fonctionnelle\n";
        echo "   ✅ Workflow fluide et sans erreur\n";
        echo "   ✅ Accès direct à la gestion des notes\n\n";
        
        echo "🎯 MISSION:\n";
        echo "Résoudre l'erreur 'enregistrement non trouvé' causée par la section cachée!\n";
        echo "Implémenter une logique de fallback intelligente!\n";
        echo "Assurer un workflow fluide pour la saisie des notes!\n";
    }
}
