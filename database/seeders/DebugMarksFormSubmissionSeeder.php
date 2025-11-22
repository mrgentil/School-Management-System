<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\SupportTeam\MarkController;
use App\Http\Requests\Mark\MarkSelector;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\Exam;
use App\Models\Subject;
use App\Repositories\StudentRepo;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use ReflectionClass;

class DebugMarksFormSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 DEBUG COMPLET DE LA SOUMISSION DU FORMULAIRE MARKS...\n\n";
        
        // Paramètres de test (ceux qui causent l'erreur)
        $testData = [
            'exam_id' => 3,
            'my_class_id' => 40,
            'section_id' => null, // Simuler le champ vide
            'subject_id' => 248
        ];
        
        echo "📋 DONNÉES DE TEST:\n";
        foreach ($testData as $key => $value) {
            echo "   ├─ {$key}: " . ($value ?? 'NULL') . "\n";
        }
        echo "\n";
        
        // Étape 1: Vérifier que tous les IDs existent
        echo "🔍 ÉTAPE 1: VÉRIFICATION DES DONNÉES DE BASE:\n";
        
        $exam = Exam::find($testData['exam_id']);
        echo "   ├─ Examen (ID {$testData['exam_id']}): " . ($exam ? "✅ {$exam->name}" : "❌ Non trouvé") . "\n";
        
        $class = MyClass::find($testData['my_class_id']);
        echo "   ├─ Classe (ID {$testData['my_class_id']}): " . ($class ? "✅ {$class->name}" : "❌ Non trouvée") . "\n";
        
        $subject = Subject::find($testData['subject_id']);
        echo "   ├─ Matière (ID {$testData['subject_id']}): " . ($subject ? "✅ {$subject->name}" : "❌ Non trouvée") . "\n";
        
        if (!$exam || !$class || !$subject) {
            echo "   └─ ❌ ERREUR: Données de base manquantes!\n";
            return;
        }
        echo "\n";
        
        // Étape 2: Simuler la méthode findSectionWithStudents
        echo "🔍 ÉTAPE 2: TEST DE findSectionWithStudents:\n";
        
        $controller = app(MarkController::class);
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('findSectionWithStudents');
        $method->setAccessible(true);
        
        try {
            $foundSectionId = $method->invoke($controller, $testData['my_class_id']);
            echo "   ├─ Section trouvée par la méthode: " . ($foundSectionId ?? 'NULL') . "\n";
            
            if ($foundSectionId) {
                $foundSection = Section::find($foundSectionId);
                echo "   ├─ Nom de la section: " . ($foundSection ? $foundSection->name : 'Section non trouvée') . "\n";
                
                // Mettre à jour nos données de test
                $testData['section_id'] = $foundSectionId;
                echo "   └─ ✅ section_id mis à jour: {$foundSectionId}\n";
            } else {
                echo "   └─ ❌ Aucune section trouvée!\n";
            }
        } catch (\Exception $e) {
            echo "   └─ ❌ Erreur dans findSectionWithStudents: " . $e->getMessage() . "\n";
        }
        echo "\n";
        
        // Étape 3: Vérifier les sections de la classe
        echo "🔍 ÉTAPE 3: ANALYSE DES SECTIONS DE LA CLASSE:\n";
        
        $sections = $class->section;
        echo "   ├─ Nombre de sections: " . ($sections ? $sections->count() : 0) . "\n";
        
        if ($sections && $sections->count() > 0) {
            foreach ($sections as $section) {
                $studentCount = StudentRecord::where('my_class_id', $testData['my_class_id'])
                    ->where('section_id', $section->id)
                    ->where('session', Qs::getSetting('current_session'))
                    ->count();
                
                echo "   ├─ Section {$section->name} (ID: {$section->id}): {$studentCount} étudiants\n";
            }
        } else {
            echo "   └─ ❌ Aucune section trouvée pour cette classe!\n";
        }
        echo "\n";
        
        // Étape 4: Test de recherche d'étudiants
        echo "🔍 ÉTAPE 4: TEST DE RECHERCHE D'ÉTUDIANTS:\n";
        
        $studentRepo = new StudentRepo();
        $searchParams = [
            'my_class_id' => $testData['my_class_id'],
            'section_id' => $testData['section_id'],
            'session' => Qs::getSetting('current_session')
        ];
        
        echo "   ├─ Paramètres de recherche:\n";
        foreach ($searchParams as $key => $value) {
            echo "   │  ├─ {$key}: " . ($value ?? 'NULL') . "\n";
        }
        
        try {
            $students = $studentRepo->getRecord($searchParams)->get();
            echo "   ├─ Étudiants trouvés: " . $students->count() . "\n";
            
            if ($students->count() > 0) {
                echo "   ├─ ✅ SUCCESS: Des étudiants ont été trouvés!\n";
                foreach ($students->take(3) as $student) {
                    echo "   │  ├─ " . $student->user->name . " (ID: {$student->user_id})\n";
                }
            } else {
                echo "   ├─ ❌ PROBLÈME: Aucun étudiant trouvé!\n";
                
                // Recherche alternative
                echo "   ├─ 🔍 Recherche alternative (toutes sections):\n";
                $allStudents = StudentRecord::where('my_class_id', $testData['my_class_id'])
                    ->where('session', Qs::getSetting('current_session'))
                    ->with('user')
                    ->get();
                
                echo "   │  ├─ Étudiants dans la classe: " . $allStudents->count() . "\n";
                
                if ($allStudents->count() > 0) {
                    $sectionsWithStudents = $allStudents->groupBy('section_id');
                    echo "   │  ├─ Sections avec étudiants:\n";
                    foreach ($sectionsWithStudents as $sectionId => $studentsInSection) {
                        $sectionName = Section::find($sectionId)->name ?? 'Section inconnue';
                        echo "   │  │  ├─ Section {$sectionName} (ID: {$sectionId}): " . $studentsInSection->count() . " étudiants\n";
                    }
                }
            }
        } catch (\Exception $e) {
            echo "   └─ ❌ Erreur lors de la recherche: " . $e->getMessage() . "\n";
        }
        echo "\n";
        
        // Étape 5: Simuler complètement la méthode selector
        echo "🔍 ÉTAPE 5: SIMULATION COMPLÈTE DE LA MÉTHODE SELECTOR:\n";
        
        try {
            // Créer une fausse request
            $request = new Request($testData);
            
            echo "   ├─ Request créée avec les données de test\n";
            
            // Simuler le début de la méthode selector
            $data = $request->only(['exam_id', 'my_class_id', 'section_id', 'subject_id']);
            echo "   ├─ Données extraites: " . json_encode($data) . "\n";
            
            // Simuler la logique de section
            if (empty($request->section_id)) {
                echo "   ├─ section_id est vide, recherche automatique...\n";
                
                $sectionWithStudents = $method->invoke($controller, $request->my_class_id);
                if ($sectionWithStudents) {
                    $data['section_id'] = $sectionWithStudents;
                    echo "   ├─ Section automatiquement trouvée: {$sectionWithStudents}\n";
                } else {
                    echo "   ├─ ❌ Aucune section trouvée automatiquement\n";
                }
            }
            
            // Test final de recherche d'étudiants
            $finalSearchParams = [
                'my_class_id' => $data['my_class_id'],
                'section_id' => $data['section_id'],
                'session' => Qs::getSetting('current_session')
            ];
            
            echo "   ├─ Paramètres finaux: " . json_encode($finalSearchParams) . "\n";
            
            $finalStudents = $studentRepo->getRecord($finalSearchParams)->get();
            echo "   ├─ Étudiants trouvés (final): " . $finalStudents->count() . "\n";
            
            if ($finalStudents->count() > 0) {
                echo "   └─ ✅ SUCCESS: La méthode devrait fonctionner!\n";
            } else {
                echo "   └─ ❌ ÉCHEC: La méthode échouera toujours!\n";
            }
            
        } catch (\Exception $e) {
            echo "   └─ ❌ Erreur dans la simulation: " . $e->getMessage() . "\n";
        }
        echo "\n";
        
        echo "🎯 DIAGNOSTIC FINAL:\n";
        
        if ($finalStudents->count() > 0) {
            echo "   ✅ Le problème devrait être résolu!\n";
            echo "   ✅ La méthode findSectionWithStudents fonctionne!\n";
            echo "   ✅ Des étudiants sont trouvés!\n";
        } else {
            echo "   ❌ Le problème persiste!\n";
            echo "   ❌ Causes possibles:\n";
            echo "      ├─ Aucun étudiant dans cette classe pour l'année courante\n";
            echo "      ├─ Problème de données dans la base\n";
            echo "      ├─ Année académique incorrecte\n";
            echo "      └─ Relations entre tables cassées\n";
        }
        
        echo "\n🔧 SOLUTIONS RECOMMANDÉES:\n";
        if ($finalStudents->count() == 0) {
            echo "   ├─ 1️⃣ Vérifier l'année académique courante\n";
            echo "   ├─ 2️⃣ Ajouter des étudiants à la classe 40\n";
            echo "   ├─ 3️⃣ Vérifier les relations section-classe\n";
            echo "   ├─ 4️⃣ Tester avec une autre classe\n";
            echo "   └─ 5️⃣ Vérifier les données de test\n";
        } else {
            echo "   ├─ ✅ Tester l'interface maintenant\n";
            echo "   └─ ✅ Le problème devrait être résolu\n";
        }
        
        echo "\n🌐 URL DE TEST: http://localhost:8000/marks\n";
        echo "🎉 DEBUG TERMINÉ!\n";
    }
}
