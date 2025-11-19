<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Option;
use App\Models\AcademicSection;
use App\Models\ClassType;

class TestAutoSectionSeeder extends Seeder
{
    public function run(): void
    {
        echo "🧪 TEST SECTION ACADÉMIQUE AUTOMATIQUE...\n\n";
        
        // Récupérer les données
        $classType = ClassType::first();
        $option = Option::where('name', 'Electronique')->first();
        
        if (!$classType || !$option) {
            echo "❌ Données manquantes pour le test\n";
            return;
        }
        
        echo "📋 DONNÉES UTILISÉES:\n";
        echo "   ├─ Type de classe: {$classType->name}\n";
        echo "   ├─ Option: {$option->name}\n";
        echo "   └─ Section de l'option: " . ($option->academic_section ? $option->academic_section->name : 'AUCUNE') . "\n\n";
        
        // Simuler la création via le contrôleur
        $data = [
            'class_type_id' => $classType->id,
            'academic_level' => '4ème',
            'division' => 'B',
            'academic_option' => 'Électronique',
            'option_id' => $option->id,
        ];
        
        // Logique du contrôleur : récupérer automatiquement la section
        if ($option && $option->academic_section_id) {
            $data['academic_section_id'] = $option->academic_section_id;
        }
        
        // Générer le nom
        $nameParts = [$data['academic_level'], $data['division'], $data['academic_option']];
        $data['name'] = implode(' ', $nameParts);
        
        // Créer la classe
        $newClass = MyClass::create($data);
        
        echo "✅ CLASSE CRÉÉE: {$newClass->name} (ID: {$newClass->id})\n\n";
        
        // Vérifier les relations
        echo "🔗 VÉRIFICATION DES RELATIONS:\n";
        echo "   ├─ Option: " . ($newClass->option ? $newClass->option->name : 'NON CONNECTÉE') . "\n";
        echo "   ├─ Section Académique (via option): " . ($newClass->option && $newClass->option->academic_section ? $newClass->option->academic_section->name : 'NON CONNECTÉE') . "\n";
        echo "   ├─ Section Académique (directe): " . ($newClass->academicSection ? $newClass->academicSection->name : 'NON CONNECTÉE') . "\n";
        echo "   └─ Nom complet: " . ($newClass->full_name ?: 'N/A') . "\n\n";
        
        // Vérifier que les deux sections correspondent
        $optionSection = $newClass->option ? $newClass->option->academic_section : null;
        $directSection = $newClass->academicSection;
        
        if ($optionSection && $directSection && $optionSection->id === $directSection->id) {
            echo "✅ SUCCÈS! Les sections correspondent parfaitement.\n";
        } else {
            echo "❌ PROBLÈME! Les sections ne correspondent pas.\n";
        }
        
        // Nettoyer
        $newClass->delete();
        echo "\n🧹 Classe de test supprimée.\n";
        echo "🎉 TEST TERMINÉ!\n";
    }
}
