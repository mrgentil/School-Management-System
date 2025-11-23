<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckStudentRecordsTableStructureSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 VÉRIFICATION DE LA STRUCTURE DE LA TABLE STUDENT_RECORDS...\n\n";
        
        // Vérifier si la table student_records existe
        if (!Schema::hasTable('student_records')) {
            echo "❌ La table 'student_records' n'existe pas!\n";
            return;
        }
        
        echo "✅ Table 'student_records' trouvée\n\n";
        
        // Récupérer la structure de la table
        $columns = Schema::getColumnListing('student_records');
        
        echo "📋 COLONNES ACTUELLES:\n";
        foreach ($columns as $column) {
            echo "   ├─ {$column}\n";
        }
        
        echo "\n🎯 COLONNES RECHERCHÉES:\n";
        
        $searchColumns = ['year', 'session', 'academic_year', 'academic_session'];
        
        foreach ($searchColumns as $searchCol) {
            $exists = in_array($searchCol, $columns);
            $status = $exists ? "✅" : "❌";
            echo "   {$status} {$searchCol}\n";
        }
        
        // Vérifier quelques enregistrements
        echo "\n📊 ÉCHANTILLON DE DONNÉES:\n";
        
        try {
            $sampleRecords = DB::table('student_records')->limit(3)->get();
            
            if ($sampleRecords->count() > 0) {
                echo "   ├─ Nombre d'enregistrements: " . DB::table('student_records')->count() . "\n";
                echo "   ├─ Échantillon:\n";
                
                foreach ($sampleRecords as $record) {
                    echo "      ├─ ID: {$record->id}\n";
                    
                    // Afficher les colonnes importantes
                    foreach ((array)$record as $key => $value) {
                        if (in_array($key, ['user_id', 'my_class_id', 'section_id', 'session', 'year', 'academic_year'])) {
                            echo "         │  {$key}: {$value}\n";
                        }
                    }
                    echo "      └─ ---\n";
                }
            } else {
                echo "   ├─ ⚠️ Aucun enregistrement trouvé\n";
            }
            
        } catch (\Exception $e) {
            echo "   ❌ Erreur lors de la lecture: " . $e->getMessage() . "\n";
        }
        
        echo "\n🔧 SOLUTION RECOMMANDÉE:\n";
        
        if (in_array('session', $columns)) {
            echo "   ✅ Utiliser la colonne 'session' au lieu de 'year'\n";
            echo "   ├─ Remplacer: ->where('year', \$year)\n";
            echo "   └─ Par: ->where('session', \$year)\n\n";
        } else {
            echo "   ⚠️ Aucune colonne d'année académique trouvée\n";
            echo "   ├─ Option 1: Supprimer le filtre par année\n";
            echo "   ├─ Option 2: Ajouter une migration pour la colonne\n";
            echo "   └─ Option 3: Utiliser une autre table de référence\n\n";
        }
        
        echo "🎯 PROCHAINES ÉTAPES:\n";
        echo "1. Corriger le service ProclamationCalculationService\n";
        echo "2. Utiliser la bonne colonne pour filtrer par année\n";
        echo "3. Tester à nouveau les proclamations\n";
    }
}
