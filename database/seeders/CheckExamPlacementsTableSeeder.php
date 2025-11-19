<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CheckExamPlacementsTableSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 VÉRIFICATION DE LA TABLE exam_student_placements...\n\n";
        
        try {
            $columns = DB::select('DESCRIBE exam_student_placements');
            
            echo "📋 COLONNES EXISTANTES:\n";
            foreach ($columns as $column) {
                echo "   ├─ {$column->Field} ({$column->Type})\n";
            }
            
            echo "\n✅ Table trouvée avec " . count($columns) . " colonnes.\n";
            
        } catch (\Exception $e) {
            echo "❌ ERREUR: " . $e->getMessage() . "\n";
        }
    }
}
