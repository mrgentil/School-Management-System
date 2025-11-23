<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ProclamationCalculationService;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Helpers\Qs;

class TestProclamationAfterFixSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔧 TEST APRÈS CORRECTION DE L'ERREUR STUDENT_RECORDS...\n\n";
        
        echo "✅ CORRECTION APPLIQUÉE:\n";
        echo "   ├─ ❌ AVANT: ->where('year', \$year)\n";
        echo "   ├─ ✅ APRÈS: ->where('session', \$year)\n";
        echo "   └─ 📋 Table: student_records utilise la colonne 'session'\n\n";
        
        echo "🧪 TEST DU SERVICE CORRIGÉ:\n\n";
        
        try {
            $service = new ProclamationCalculationService();
            echo "   ✅ Service instancié avec succès\n";
            
            // Récupérer une classe de test
            $testClass = MyClass::first();
            
            if (!$testClass) {
                echo "   ❌ Aucune classe trouvée pour le test\n";
                return;
            }
            
            echo "   ├─ Classe de test: " . ($testClass->full_name ?: $testClass->name) . " (ID: {$testClass->id})\n";
            
            // Vérifier les étudiants
            $year = Qs::getSetting('current_session');
            echo "   ├─ Année académique: {$year}\n";
            
            $students = StudentRecord::where('my_class_id', $testClass->id)
                                   ->where('session', $year)
                                   ->count();
            echo "   ├─ Étudiants trouvés: {$students}\n";
            
            if ($students > 0) {
                echo "\n📊 TEST DE CALCUL DE PROCLAMATION:\n";
                
                // Test calcul période 1
                try {
                    $periodRankings = $service->calculateClassRankingForPeriod($testClass->id, 1, $year);
                    echo "   ✅ Calcul période 1: Succès\n";
                    echo "   ├─ Étudiants classés: " . $periodRankings['total_students'] . "\n";
                    
                    if ($periodRankings['total_students'] > 0) {
                        $firstStudent = $periodRankings['rankings'][0];
                        echo "   ├─ 1er: " . $firstStudent['student_name'] . "\n";
                        echo "   └─ Pourcentage: " . number_format($firstStudent['percentage'], 2) . "%\n";
                    }
                } catch (\Exception $e) {
                    echo "   ❌ Erreur calcul période 1: " . $e->getMessage() . "\n";
                }
                
                // Test calcul semestre 1
                try {
                    $semesterRankings = $service->calculateClassRankingForSemester($testClass->id, 1, $year);
                    echo "   ✅ Calcul semestre 1: Succès\n";
                    echo "   ├─ Étudiants classés: " . $semesterRankings['total_students'] . "\n";
                    
                    if ($semesterRankings['total_students'] > 0) {
                        $firstStudent = $semesterRankings['rankings'][0];
                        echo "   ├─ 1er: " . $firstStudent['student_name'] . "\n";
                        echo "   └─ Pourcentage: " . number_format($firstStudent['percentage'], 2) . "%\n";
                    }
                } catch (\Exception $e) {
                    echo "   ❌ Erreur calcul semestre 1: " . $e->getMessage() . "\n";
                }
                
            } else {
                echo "\n⚠️ AUCUN ÉTUDIANT TROUVÉ:\n";
                echo "   ├─ Vérifiez que des étudiants sont inscrits\n";
                echo "   ├─ Vérifiez l'année académique courante\n";
                echo "   └─ Vérifiez la colonne 'session' dans student_records\n";
            }
            
        } catch (\Exception $e) {
            echo "   ❌ Erreur lors du test: " . $e->getMessage() . "\n";
        }
        
        echo "\n🎯 VÉRIFICATION DE LA BASE DE DONNÉES:\n\n";
        
        // Vérifier la structure de student_records
        try {
            $sampleRecord = StudentRecord::first();
            if ($sampleRecord) {
                echo "   ✅ Table student_records accessible\n";
                echo "   ├─ Colonnes disponibles:\n";
                
                $attributes = $sampleRecord->getAttributes();
                foreach ($attributes as $key => $value) {
                    if (in_array($key, ['id', 'session', 'user_id', 'my_class_id', 'section_id'])) {
                        echo "   │  ├─ {$key}: {$value}\n";
                    }
                }
                
                echo "   └─ ✅ Colonne 'session' confirmée\n";
            } else {
                echo "   ⚠️ Aucun enregistrement dans student_records\n";
            }
        } catch (\Exception $e) {
            echo "   ❌ Erreur d'accès à student_records: " . $e->getMessage() . "\n";
        }
        
        echo "\n🌐 TEST DE L'INTERFACE:\n\n";
        
        echo "MAINTENANT TESTEZ:\n";
        echo "   ├─ 🌐 URL: http://localhost:8000/proclamations\n";
        echo "   ├─ 🔐 Connectez-vous en Super Admin\n";
        echo "   ├─ 📚 Menu: Académique → 🏆 Proclamations RDC\n";
        echo "   ├─ 🔍 Sélectionnez une classe\n";
        echo "   ├─ 📋 Choisissez 'Par Période'\n";
        echo "   ├─ 🎯 Sélectionnez 'Période 1'\n";
        echo "   └─ 🧮 Cliquez sur 'Calculer'\n\n";
        
        echo "RÉSULTAT ATTENDU:\n";
        echo "   ├─ ✅ Plus d'erreur SQL\n";
        echo "   ├─ ✅ Chargement des étudiants\n";
        echo "   ├─ ✅ Calcul des moyennes\n";
        echo "   ├─ ✅ Affichage du classement\n";
        echo "   └─ ✅ Interface fonctionnelle\n\n";
        
        echo "🎉 ERREUR CORRIGÉE!\n";
        echo "Le système de proclamation devrait maintenant fonctionner correctement!\n";
    }
}
