<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CheckTableStructureSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 VÉRIFICATION DES STRUCTURES DE TABLES...\n\n";
        
        echo "📋 STRUCTURE TABLE my_classes:\n";
        $myClassesColumns = DB::select('DESCRIBE my_classes');
        foreach ($myClassesColumns as $column) {
            echo "   ├─ {$column->Field} - {$column->Type} - {$column->Key}\n";
        }
        
        echo "\n📋 STRUCTURE TABLE subjects:\n";
        $subjectsColumns = DB::select('DESCRIBE subjects');
        foreach ($subjectsColumns as $column) {
            echo "   ├─ {$column->Field} - {$column->Type} - {$column->Key}\n";
        }
        
        echo "\n🎯 DIAGNOSTIC:\n";
        $myClassesId = collect($myClassesColumns)->where('Field', 'id')->first();
        $subjectsId = collect($subjectsColumns)->where('Field', 'id')->first();
        
        echo "   ├─ my_classes.id: {$myClassesId->Type}\n";
        echo "   └─ subjects.id: {$subjectsId->Type}\n";
        
        echo "\n💡 SOLUTION:\n";
        echo "   ├─ Utiliser le même type pour les clés étrangères\n";
        echo "   └─ Probablement unsignedInteger au lieu de unsignedBigInteger\n";
    }
}
