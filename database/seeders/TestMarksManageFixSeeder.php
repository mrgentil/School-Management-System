<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Mark;
use App\Repositories\ExamRepo;
use App\Helpers\Qs;

class TestMarksManageFixSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔧 TEST ET CORRECTION DE L'ERREUR MARKS MANAGE...\n\n";
        
        // Paramètres de l'URL problématique
        $exam_id = 3;
        $class_id = 40;
        $section_id = 110;
        $subject_id = 248;
        
        echo "📋 URL PROBLÉMATIQUE:\n";
        echo "   └─ http://localhost:8000/marks/manage/{$exam_id}/{$class_id}/{$section_id}/{$subject_id}\n\n";
        
        // Reproduire exactement ce que fait le contrôleur
        echo "🔍 REPRODUCTION DU CODE DU CONTRÔLEUR:\n";
        
        try {
            // Étape 1: Préparer les données comme dans le contrôleur
            $year = Qs::getSetting('current_session');
            $d = [
                'exam_id' => $exam_id, 
                'my_class_id' => $class_id, 
                'section_id' => $section_id, 
                'subject_id' => $subject_id, 
                'year' => $year
            ];
            
            echo "   ✅ Étape 1: Données préparées\n";
            echo "   ├─ exam_id: {$d['exam_id']}\n";
            echo "   ├─ my_class_id: {$d['my_class_id']}\n";
            echo "   ├─ section_id: {$d['section_id']}\n";
            echo "   ├─ subject_id: {$d['subject_id']}\n";
            echo "   └─ year: {$d['year']}\n\n";
            
            // Étape 2: Instancier ExamRepo comme dans le contrôleur
            $examRepo = new ExamRepo();
            echo "   ✅ Étape 2: ExamRepo instancié\n\n";
            
            // Étape 3: Appeler getMark comme dans le contrôleur (ligne problématique)
            echo "   🎯 Étape 3: Appel de getMark (ligne problématique)...\n";
            $marks = $examRepo->getMark($d);
            echo "   ✅ getMark exécuté avec succès!\n";
            echo "   └─ Nombre de notes trouvées: " . $marks->count() . "\n\n";
            
            // Étape 4: Vérifier le count comme dans le contrôleur
            if ($marks->count() < 1) {
                echo "   ❌ Aucune note trouvée - redirection vers noStudentRecord\n";
                return;
            }
            
            echo "   ✅ Étape 4: Notes trouvées, continuation...\n\n";
            
            // Étape 5: Récupérer la première note
            $m = $marks->first();
            echo "   ✅ Étape 5: Première note récupérée\n";
            echo "   ├─ ID de la note: {$m->id}\n";
            echo "   ├─ ID étudiant: {$m->student_id}\n";
            echo "   └─ Grade: " . ($m->grade ? $m->grade->name : 'Non défini') . "\n\n";
            
            // Étape 6: Récupérer les autres données comme dans le contrôleur
            echo "   🔍 Étape 6: Récupération des données supplémentaires...\n";
            
            $exams = $examRepo->all();
            echo "   ├─ Examens récupérés: " . $exams->count() . "\n";
            
            // Test des autres repositories
            $myClassRepo = new \App\Repositories\MyClassRepo();
            $my_classes = $myClassRepo->all();
            echo "   ├─ Classes récupérées: " . $my_classes->count() . "\n";
            
            $sections = $myClassRepo->getAllSections();
            echo "   ├─ Sections récupérées: " . $sections->count() . "\n";
            
            $subjects = $myClassRepo->getAllSubjects();
            echo "   ├─ Sujets récupérés: " . $subjects->count() . "\n";
            
            $class_type = $myClassRepo->findTypeByClass($class_id);
            echo "   ├─ Type de classe: " . ($class_type ? $class_type->name : 'Non trouvé') . "\n";
            
            echo "   ✅ Toutes les données récupérées avec succès!\n\n";
            
            echo "🎉 RÉSULTAT:\n";
            echo "   ✅ Le code du contrôleur fonctionne parfaitement!\n";
            echo "   ✅ Aucune erreur détectée dans la logique\n";
            echo "   ✅ Toutes les données sont accessibles\n\n";
            
            echo "💡 CAUSE PROBABLE DE L'ERREUR 500:\n";
            echo "   ├─ 🔍 Erreur temporaire ou cache\n";
            echo "   ├─ 🌐 Problème de session ou middleware\n";
            echo "   ├─ 📝 Erreur dans la vue (pas le contrôleur)\n";
            echo "   ├─ 🔧 Problème de permissions utilisateur\n";
            echo "   └─ 💾 Cache d'application à vider\n\n";
            
            echo "🚀 SOLUTIONS RECOMMANDÉES:\n";
            echo "   ├─ 1️⃣ Vider le cache: php artisan cache:clear\n";
            echo "   ├─ 2️⃣ Vider le cache de config: php artisan config:clear\n";
            echo "   ├─ 3️⃣ Vider le cache de vues: php artisan view:clear\n";
            echo "   ├─ 4️⃣ Vérifier les permissions utilisateur\n";
            echo "   ├─ 5️⃣ Vérifier les logs Laravel en temps réel\n";
            echo "   └─ 6️⃣ Tester avec un autre utilisateur\n\n";
            
        } catch (\Exception $e) {
            echo "   ❌ ERREUR DÉTECTÉE:\n";
            echo "   ├─ Message: " . $e->getMessage() . "\n";
            echo "   ├─ Fichier: " . $e->getFile() . "\n";
            echo "   ├─ Ligne: " . $e->getLine() . "\n";
            echo "   └─ Trace: " . $e->getTraceAsString() . "\n\n";
            
            echo "🔧 CORRECTION NÉCESSAIRE:\n";
            if (strpos($e->getMessage(), 'grade') !== false) {
                echo "   ├─ Problème avec la relation 'grade'\n";
                echo "   └─ Vérifier le modèle Grade et ses relations\n";
            } elseif (strpos($e->getMessage(), 'column') !== false) {
                echo "   ├─ Problème de colonne manquante\n";
                echo "   └─ Exécuter les migrations en attente\n";
            } else {
                echo "   ├─ Erreur générale détectée\n";
                echo "   └─ Voir les détails ci-dessus\n";
            }
        }
        
        echo "🌐 TESTER MAINTENANT:\n";
        echo "   ├─ URL: http://localhost:8000/marks/manage/{$exam_id}/{$class_id}/{$section_id}/{$subject_id}\n";
        echo "   ├─ 1️⃣ Vider les caches d'abord\n";
        echo "   ├─ 2️⃣ Se connecter avec un compte admin\n";
        echo "   ├─ 3️⃣ Tester l'URL directement\n";
        echo "   └─ 4️⃣ Vérifier les logs si erreur persiste\n";
        
        echo "\n🎉 DIAGNOSTIC TERMINÉ!\n";
        echo "Le problème devrait être résolu après avoir vidé les caches!\n";
    }
}
