<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\SubjectGradeConfig;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Route;

class DebugSubjectGradeConfigInterfaceSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 DEBUG COMPLET DE L'INTERFACE SUBJECT GRADE CONFIG...\n\n";
        
        // 1. Vérifier les routes
        echo "🛣️ VÉRIFICATION DES ROUTES:\n";
        try {
            $routeExists = Route::has('subject-grades-config.index');
            echo "   ├─ Route 'subject-grades-config.index': " . ($routeExists ? "✅ Existe" : "❌ Manquante") . "\n";
            
            $showRouteExists = Route::has('subject-grades-config.show');
            echo "   ├─ Route 'subject-grades-config.show': " . ($showRouteExists ? "✅ Existe" : "❌ Manquante") . "\n";
            
            if ($routeExists) {
                $url = route('subject-grades-config.index');
                echo "   └─ URL générée: {$url}\n";
            }
        } catch (\Exception $e) {
            echo "   ❌ Erreur routes: " . $e->getMessage() . "\n";
        }
        
        echo "\n🗃️ VÉRIFICATION DE LA BASE DE DONNÉES:\n";
        
        // 2. Vérifier la table
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('subject_grades_config')) {
                echo "   ✅ Table 'subject_grades_config' existe\n";
                $count = \Illuminate\Support\Facades\DB::table('subject_grades_config')->count();
                echo "   ├─ Enregistrements: {$count}\n";
            } else {
                echo "   ❌ Table 'subject_grades_config' manquante\n";
            }
        } catch (\Exception $e) {
            echo "   ❌ Erreur table: " . $e->getMessage() . "\n";
        }
        
        // 3. Vérifier les classes
        echo "\n🏫 VÉRIFICATION DES CLASSES:\n";
        try {
            $classes = MyClass::all();
            echo "   ├─ Nombre de classes: " . $classes->count() . "\n";
            
            foreach ($classes as $class) {
                echo "   ├─ Classe ID {$class->id}: " . ($class->full_name ?: $class->name) . "\n";
                
                // Vérifier les matières pour cette classe
                $subjects = Subject::where('my_class_id', $class->id)->get();
                echo "   │  ├─ Matières: " . $subjects->count() . "\n";
                
                if ($subjects->count() > 0) {
                    foreach ($subjects as $subject) {
                        echo "   │  │  ├─ {$subject->name} (ID: {$subject->id})\n";
                    }
                } else {
                    echo "   │  │  └─ ❌ Aucune matière trouvée!\n";
                }
            }
        } catch (\Exception $e) {
            echo "   ❌ Erreur classes: " . $e->getMessage() . "\n";
        }
        
        // 4. Test du contrôleur
        echo "\n🎮 TEST DU CONTRÔLEUR:\n";
        try {
            $controller = app(\App\Http\Controllers\SupportTeam\SubjectGradeConfigController::class);
            echo "   ✅ Contrôleur instancié avec succès\n";
            
            // Test de la méthode index
            echo "   ├─ Test méthode index()...\n";
            $indexResult = $controller->index();
            echo "   │  └─ ✅ Méthode index() fonctionne\n";
            
            // Test avec une classe spécifique
            $firstClass = MyClass::first();
            if ($firstClass) {
                echo "   ├─ Test méthode show({$firstClass->id})...\n";
                $showResult = $controller->show($firstClass->id);
                echo "   │  └─ ✅ Méthode show() fonctionne\n";
            }
            
        } catch (\Exception $e) {
            echo "   ❌ Erreur contrôleur: " . $e->getMessage() . "\n";
            echo "   ├─ Fichier: " . $e->getFile() . "\n";
            echo "   └─ Ligne: " . $e->getLine() . "\n";
        }
        
        // 5. Test de la vue
        echo "\n🎨 VÉRIFICATION DE LA VUE:\n";
        $viewPath = resource_path('views/pages/support_team/subject_grades_config/index.blade.php');
        if (file_exists($viewPath)) {
            echo "   ✅ Fichier vue existe: " . basename($viewPath) . "\n";
            $viewSize = filesize($viewPath);
            echo "   ├─ Taille: " . number_format($viewSize) . " octets\n";
        } else {
            echo "   ❌ Fichier vue manquant: {$viewPath}\n";
        }
        
        // 6. Test des données pour la vue
        echo "\n📊 TEST DES DONNÉES POUR LA VUE:\n";
        try {
            $data = [];
            $data['my_classes'] = MyClass::orderBy('name')->get();
            $data['subjects'] = Subject::orderBy('name')->get();
            $data['current_year'] = Qs::getSetting('current_session');
            $data['selected_class'] = null;
            $data['configs'] = collect();
            
            echo "   ├─ Classes chargées: " . $data['my_classes']->count() . "\n";
            echo "   ├─ Matières chargées: " . $data['subjects']->count() . "\n";
            echo "   ├─ Année courante: " . $data['current_year'] . "\n";
            echo "   └─ Configs: " . $data['configs']->count() . "\n";
            
        } catch (\Exception $e) {
            echo "   ❌ Erreur données: " . $e->getMessage() . "\n";
        }
        
        // 7. Test avec une classe sélectionnée
        echo "\n🎯 TEST AVEC CLASSE SÉLECTIONNÉE:\n";
        $testClass = MyClass::first();
        if ($testClass) {
            try {
                echo "   ├─ Classe de test: " . ($testClass->full_name ?: $testClass->name) . "\n";
                
                $classSubjects = Subject::where('my_class_id', $testClass->id)->orderBy('name')->get();
                echo "   ├─ Matières trouvées: " . $classSubjects->count() . "\n";
                
                $configs = SubjectGradeConfig::getClassConfigs($testClass->id);
                echo "   ├─ Configurations existantes: " . $configs->count() . "\n";
                
                if ($classSubjects->count() > 0) {
                    echo "   ├─ ✅ La classe a des matières - l'interface devrait fonctionner\n";
                    
                    echo "   ├─ Matières détaillées:\n";
                    foreach ($classSubjects as $subject) {
                        echo "   │  ├─ {$subject->name}\n";
                    }
                } else {
                    echo "   ├─ ❌ PROBLÈME: La classe n'a pas de matières!\n";
                    echo "   └─ 💡 Solution: Créer des matières pour cette classe\n";
                }
                
            } catch (\Exception $e) {
                echo "   ❌ Erreur test classe: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ❌ Aucune classe trouvée pour les tests\n";
        }
        
        // 8. Vérification des permissions
        echo "\n🔐 VÉRIFICATION DES PERMISSIONS:\n";
        try {
            // Simuler un utilisateur super admin
            echo "   ├─ Middleware requis: teamSA (Super Admin)\n";
            echo "   ├─ Fonction helper: Qs::userIsSuperAdmin()\n";
            echo "   └─ ⚠️ Vérifiez que vous êtes connecté en Super Admin\n";
        } catch (\Exception $e) {
            echo "   ❌ Erreur permissions: " . $e->getMessage() . "\n";
        }
        
        // 9. Diagnostic final
        echo "\n🎯 DIAGNOSTIC FINAL:\n";
        
        $issues = [];
        
        if (!Route::has('subject-grades-config.index')) {
            $issues[] = "Routes manquantes";
        }
        
        if (!file_exists($viewPath)) {
            $issues[] = "Fichier vue manquant";
        }
        
        if (MyClass::count() == 0) {
            $issues[] = "Aucune classe";
        }
        
        $classesWithSubjects = 0;
        foreach (MyClass::all() as $class) {
            if (Subject::where('my_class_id', $class->id)->count() > 0) {
                $classesWithSubjects++;
            }
        }
        
        if ($classesWithSubjects == 0) {
            $issues[] = "Aucune classe avec matières";
        }
        
        if (empty($issues)) {
            echo "   ✅ TOUT SEMBLE CORRECT!\n";
            echo "   ├─ Routes: OK\n";
            echo "   ├─ Vue: OK\n";
            echo "   ├─ Classes: OK\n";
            echo "   ├─ Matières: OK\n";
            echo "   └─ Contrôleur: OK\n\n";
            
            echo "🌐 TESTEZ MAINTENANT:\n";
            echo "   ├─ URL: http://localhost:8000/subject-grades-config\n";
            echo "   ├─ Connectez-vous en Super Admin\n";
            echo "   ├─ Sélectionnez '" . ($testClass->full_name ?: $testClass->name) . "'\n";
            echo "   └─ Vous devriez voir le tableau avec " . $classSubjects->count() . " matières\n";
            
        } else {
            echo "   ❌ PROBLÈMES DÉTECTÉS:\n";
            foreach ($issues as $issue) {
                echo "   ├─ {$issue}\n";
            }
            
            echo "\n🔧 SOLUTIONS:\n";
            if (in_array("Routes manquantes", $issues)) {
                echo "   ├─ Vérifier routes/web.php\n";
            }
            if (in_array("Fichier vue manquant", $issues)) {
                echo "   ├─ Recréer le fichier vue\n";
            }
            if (in_array("Aucune classe avec matières", $issues)) {
                echo "   ├─ Créer des matières pour les classes\n";
            }
        }
        
        echo "\n🎉 DEBUG TERMINÉ!\n";
    }
}
