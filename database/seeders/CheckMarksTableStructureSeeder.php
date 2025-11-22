<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckMarksTableStructureSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 VÉRIFICATION DE LA STRUCTURE DE LA TABLE MARKS...\n\n";
        
        try {
            // Vérifier si la table existe
            if (Schema::hasTable('marks')) {
                echo "✅ Table 'marks' existe\n\n";
                
                // Obtenir la structure de la table
                $columns = DB::select('DESCRIBE marks');
                
                echo "📋 COLONNES DE LA TABLE MARKS:\n";
                foreach ($columns as $column) {
                    $nullable = $column->Null == 'YES' ? 'NULL' : 'NOT NULL';
                    $default = $column->Default ? "DEFAULT: {$column->Default}" : '';
                    echo "   ├─ {$column->Field} - {$column->Type} - {$nullable} {$default}\n";
                }
                
                echo "\n🔍 VÉRIFICATION DES COLONNES SYSTÈME RDC:\n";
                $rdcColumns = ['p1_avg', 'p2_avg', 'p3_avg', 'p4_avg', 's1_exam', 's2_exam'];
                
                foreach ($rdcColumns as $col) {
                    if (Schema::hasColumn('marks', $col)) {
                        echo "   ✅ Colonne '{$col}' existe\n";
                    } else {
                        echo "   ❌ Colonne '{$col}' manquante!\n";
                    }
                }
                
                echo "\n📊 STATISTIQUES DE LA TABLE:\n";
                $totalRows = DB::table('marks')->count();
                echo "   ├─ Nombre total d'enregistrements: {$totalRows}\n";
                
                if ($totalRows > 0) {
                    $sampleRow = DB::table('marks')->first();
                    echo "   ├─ Premier enregistrement:\n";
                    foreach ($sampleRow as $key => $value) {
                        echo "   │  ├─ {$key}: " . ($value ?? 'NULL') . "\n";
                    }
                }
                
            } else {
                echo "❌ Table 'marks' n'existe pas!\n";
            }
            
        } catch (\Exception $e) {
            echo "❌ ERREUR lors de la vérification:\n";
            echo "   ├─ Message: " . $e->getMessage() . "\n";
            echo "   ├─ Fichier: " . $e->getFile() . "\n";
            echo "   └─ Ligne: " . $e->getLine() . "\n";
        }
        
        echo "\n🎯 DIAGNOSTIC COMPLÉMENTAIRE:\n";
        
        // Vérifier les migrations en attente
        try {
            $pendingMigrations = DB::table('migrations')
                ->where('batch', '>', 0)
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();
                
            echo "   ├─ Dernières migrations appliquées:\n";
            foreach ($pendingMigrations as $migration) {
                echo "   │  ├─ {$migration->migration}\n";
            }
            
        } catch (\Exception $e) {
            echo "   ├─ Impossible de vérifier les migrations\n";
        }
        
        echo "\n🎉 VÉRIFICATION TERMINÉE!\n";
    }
}
