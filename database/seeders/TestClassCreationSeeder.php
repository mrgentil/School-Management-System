<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Option;
use App\Models\AcademicSection;
use App\Models\ClassType;

class TestClassCreationSeeder extends Seeder
{
    public function run(): void
    {
        echo "🧪 TEST DE CRÉATION DE CLASSE CONNECTÉE...\n\n";
        
        // Récupérer les données nécessaires
        $classType = ClassType::first();
        $academicSection = AcademicSection::where('name', 'Technique')->first();
        $option = Option::where('name', 'Electronique')->first();
        
        if (!$classType || !$academicSection || !$option) {
            echo "❌ Données manquantes pour le test\n";
            return;
        }
        
        echo "📋 DONNÉES UTILISÉES:\n";
        echo "   ├─ Type de classe: {$classType->name}\n";
        echo "   ├─ Section académique: {$academicSection->name}\n";
        echo "   └─ Option: {$option->name}\n\n";
        
        // Créer une nouvelle classe
        $newClass = MyClass::create([
            'name' => 'Test 5ème A Électronique',
            'class_type_id' => $classType->id,
            'academic_section_id' => $academicSection->id,
            'option_id' => $option->id,
            'academic_level' => '5ème',
            'division' => 'A',
            'academic_option' => 'Électronique'
        ]);
        
        echo "✅ CLASSE CRÉÉE: {$newClass->name} (ID: {$newClass->id})\n\n";
        
        // Vérifier les relations
        echo "🔗 VÉRIFICATION DES RELATIONS:\n";
        echo "   ├─ Academic Section: " . ($newClass->academicSection ? $newClass->academicSection->name : 'NON CONNECTÉE') . "\n";
        echo "   ├─ Option: " . ($newClass->option ? $newClass->option->name : 'NON CONNECTÉE') . "\n";
        echo "   ├─ Type de classe: " . ($newClass->class_type ? $newClass->class_type->name : 'NON CONNECTÉE') . "\n";
        echo "   └─ Nom complet: " . ($newClass->full_name ?: 'N/A') . "\n\n";
        
        // Créer une section par défaut
        \App\Models\Section::create([
            'name' => 'A',
            'my_class_id' => $newClass->id,
            'active' => 1,
            'teacher_id' => null
        ]);
        
        echo "✅ Section 'A' créée pour la classe\n";
        
        // Vérifier les sections
        $sections = $newClass->section;
        echo "📋 Sections disponibles: " . $sections->count() . "\n";
        foreach ($sections as $section) {
            echo "   └─ " . $section->name . "\n";
        }
        
        echo "\n🎉 TEST TERMINÉ! La classe est maintenant connectée à toutes les tables.\n";
    }
}
