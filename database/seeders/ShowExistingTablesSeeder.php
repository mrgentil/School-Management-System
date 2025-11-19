<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;
use App\Models\AcademicSection;
use App\Models\Section;

class ShowExistingTablesSeeder extends Seeder
{
    public function run(): void
    {
        echo "📋 CONTENU DES TABLES EXISTANTES:\n\n";

        echo "🎯 TABLE OPTIONS:\n";
        $options = Option::all();
        foreach ($options as $option) {
            echo "- {$option->name} (ID: {$option->id})\n";
        }

        echo "\n📚 TABLE ACADEMIC_SECTIONS:\n";
        $academicSections = AcademicSection::all();
        foreach ($academicSections as $section) {
            echo "- {$section->name} (ID: {$section->id})\n";
        }

        echo "\n🏫 EXEMPLE DE SECTIONS (divisions):\n";
        $sections = Section::take(10)->get();
        foreach ($sections as $section) {
            echo "- {$section->name} (Classe ID: {$section->my_class_id})\n";
        }
        
        echo "\n✅ TERMINÉ!\n";
    }
}
