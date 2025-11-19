<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentRecord;
use App\Models\Section;
use App\Models\MyClass;

class DiagnosticSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 DIAGNOSTIC COMPLET...\n\n";
        
        // 1. Vérifier la classe
        $class = MyClass::find(6);
        echo "1. CLASSE:\n";
        echo "   - ID: {$class->id}\n";
        echo "   - Nom: {$class->name}\n\n";
        
        // 2. Vérifier les sections
        echo "2. SECTIONS DE CETTE CLASSE:\n";
        $sections = Section::where('my_class_id', 6)->get();
        foreach ($sections as $sec) {
            echo "   - {$sec->name} (ID: {$sec->id})\n";
        }
        echo "\n";
        
        // 3. Vérifier les étudiants
        echo "3. ÉTUDIANTS DANS CETTE CLASSE:\n";
        $students = StudentRecord::where('my_class_id', 6)->with('user')->get();
        foreach ($students as $s) {
            echo "   - {$s->user->name} → Section ID: {$s->section_id}\n";
        }
        echo "\n";
        
        // 4. Compter par section
        echo "4. RÉPARTITION PAR SECTION:\n";
        foreach ($sections as $sec) {
            $count = StudentRecord::where('my_class_id', 6)
                ->where('section_id', $sec->id)
                ->count();
            echo "   - Section {$sec->name} (ID: {$sec->id}): {$count} étudiants\n";
        }
        echo "\n";
        
        // 5. Corriger si nécessaire
        echo "5. CORRECTION AUTOMATIQUE:\n";
        $sectionsIds = $sections->pluck('id')->toArray();
        
        foreach ($students as $index => $student) {
            if (count($sectionsIds) > 0) {
                $newSectionId = $sectionsIds[$index % count($sectionsIds)];
                $student->update(['section_id' => $newSectionId]);
                
                $sectionName = $sections->where('id', $newSectionId)->first()->name;
                echo "   ✅ {$student->user->name} → Section {$sectionName} (ID: {$newSectionId})\n";
            }
        }
        
        echo "\n🎉 DIAGNOSTIC TERMINÉ!\n";
    }
}
