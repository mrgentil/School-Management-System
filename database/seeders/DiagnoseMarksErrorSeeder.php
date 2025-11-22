<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Mark;
use App\Helpers\Qs;

class DiagnoseMarksErrorSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 DIAGNOSTIC DE L'ERREUR 500 - MARKS MANAGE...\n\n";
        
        // Paramètres de l'URL problématique
        $exam_id = 3;
        $class_id = 40;
        $section_id = 110;
        $subject_id = 248;
        
        echo "📋 PARAMÈTRES DE L'URL:\n";
        echo "   ├─ exam_id: {$exam_id}\n";
        echo "   ├─ class_id: {$class_id}\n";
        echo "   ├─ section_id: {$section_id}\n";
        echo "   └─ subject_id: {$subject_id}\n\n";
        
        // Vérification de l'examen
        echo "🎯 VÉRIFICATION DE L'EXAMEN:\n";
        $exam = Exam::find($exam_id);
        if ($exam) {
            echo "   ✅ Examen trouvé: {$exam->name}\n";
            echo "   ├─ Semestre: {$exam->semester}\n";
            echo "   └─ Année: {$exam->year}\n";
        } else {
            echo "   ❌ ERREUR: Examen avec ID {$exam_id} non trouvé!\n";
        }
        echo "\n";
        
        // Vérification de la classe
        echo "🏫 VÉRIFICATION DE LA CLASSE:\n";
        $class = MyClass::find($class_id);
        if ($class) {
            echo "   ✅ Classe trouvée: {$class->name}\n";
            if (method_exists($class, 'full_name')) {
                echo "   ├─ Nom complet: " . ($class->full_name ?: 'Non défini') . "\n";
            }
        } else {
            echo "   ❌ ERREUR: Classe avec ID {$class_id} non trouvée!\n";
        }
        echo "\n";
        
        // Vérification de la section
        echo "📚 VÉRIFICATION DE LA SECTION:\n";
        $section = Section::find($section_id);
        if ($section) {
            echo "   ✅ Section trouvée: {$section->name}\n";
        } else {
            echo "   ❌ ERREUR: Section avec ID {$section_id} non trouvée!\n";
        }
        echo "\n";
        
        // Vérification du sujet
        echo "📖 VÉRIFICATION DU SUJET:\n";
        $subject = Subject::find($subject_id);
        if ($subject) {
            echo "   ✅ Sujet trouvé: {$subject->name}\n";
        } else {
            echo "   ❌ ERREUR: Sujet avec ID {$subject_id} non trouvé!\n";
        }
        echo "\n";
        
        // Vérification de l'année courante
        echo "📅 VÉRIFICATION DE L'ANNÉE COURANTE:\n";
        $current_year = Qs::getSetting('current_session');
        echo "   ├─ Année courante: {$current_year}\n";
        echo "\n";
        
        // Test de la requête getMark
        echo "🔍 TEST DE LA REQUÊTE getMark:\n";
        try {
            $data = [
                'exam_id' => $exam_id,
                'my_class_id' => $class_id,
                'section_id' => $section_id,
                'subject_id' => $subject_id,
                'year' => $current_year
            ];
            
            echo "   ├─ Paramètres de recherche:\n";
            foreach ($data as $key => $value) {
                echo "   │  ├─ {$key}: {$value}\n";
            }
            
            // Test sans relation
            echo "   ├─ Test sans relation 'grade':\n";
            $marks_without_grade = Mark::where($data)->get();
            echo "   │  └─ Nombre de notes trouvées: " . $marks_without_grade->count() . "\n";
            
            // Test avec relation
            echo "   ├─ Test avec relation 'grade':\n";
            $marks_with_grade = Mark::where($data)->with('grade')->get();
            echo "   │  └─ Nombre de notes avec grade: " . $marks_with_grade->count() . "\n";
            
            if ($marks_with_grade->count() > 0) {
                $first_mark = $marks_with_grade->first();
                echo "   ├─ Première note:\n";
                echo "   │  ├─ ID: {$first_mark->id}\n";
                echo "   │  ├─ Student ID: {$first_mark->student_id}\n";
                if ($first_mark->grade) {
                    echo "   │  └─ Grade: {$first_mark->grade->name}\n";
                } else {
                    echo "   │  └─ Grade: Non défini\n";
                }
            }
            
        } catch (\Exception $e) {
            echo "   ❌ ERREUR lors de la requête getMark:\n";
            echo "   ├─ Message: " . $e->getMessage() . "\n";
            echo "   ├─ Fichier: " . $e->getFile() . "\n";
            echo "   └─ Ligne: " . $e->getLine() . "\n";
        }
        echo "\n";
        
        // Vérification de la structure de la table marks
        echo "🗃️ VÉRIFICATION DE LA TABLE MARKS:\n";
        try {
            $sample_marks = Mark::take(3)->get();
            echo "   ├─ Nombre total de notes: " . Mark::count() . "\n";
            echo "   ├─ Échantillon de 3 notes:\n";
            foreach ($sample_marks as $index => $mark) {
                echo "   │  ├─ Note " . ($index + 1) . ":\n";
                echo "   │  │  ├─ ID: {$mark->id}\n";
                echo "   │  │  ├─ Student ID: {$mark->student_id}\n";
                echo "   │  │  ├─ Exam ID: {$mark->exam_id}\n";
                echo "   │  │  ├─ Class ID: {$mark->my_class_id}\n";
                echo "   │  │  └─ Subject ID: {$mark->subject_id}\n";
            }
        } catch (\Exception $e) {
            echo "   ❌ ERREUR lors de l'accès à la table marks:\n";
            echo "   └─ Message: " . $e->getMessage() . "\n";
        }
        echo "\n";
        
        // Vérification du modèle Mark
        echo "🔧 VÉRIFICATION DU MODÈLE MARK:\n";
        try {
            $mark_model = new Mark();
            echo "   ├─ Modèle Mark instancié avec succès\n";
            
            // Vérifier la relation grade
            if (method_exists($mark_model, 'grade')) {
                echo "   ├─ Relation 'grade' existe\n";
            } else {
                echo "   ❌ Relation 'grade' n'existe pas!\n";
            }
            
        } catch (\Exception $e) {
            echo "   ❌ ERREUR avec le modèle Mark:\n";
            echo "   └─ Message: " . $e->getMessage() . "\n";
        }
        echo "\n";
        
        echo "🎯 RECOMMANDATIONS:\n";
        if (!$exam) {
            echo "   ├─ ❌ Créer ou vérifier l'examen avec ID {$exam_id}\n";
        }
        if (!$class) {
            echo "   ├─ ❌ Créer ou vérifier la classe avec ID {$class_id}\n";
        }
        if (!$section) {
            echo "   ├─ ❌ Créer ou vérifier la section avec ID {$section_id}\n";
        }
        if (!$subject) {
            echo "   ├─ ❌ Créer ou vérifier le sujet avec ID {$subject_id}\n";
        }
        
        echo "   ├─ 🔍 Vérifier les logs Laravel pour plus de détails\n";
        echo "   ├─ 🗃️ Vérifier l'intégrité de la base de données\n";
        echo "   └─ 🔧 Vérifier les relations dans les modèles\n";
        
        echo "\n🎉 DIAGNOSTIC TERMINÉ!\n";
        echo "Utilisez ces informations pour identifier la cause de l'erreur 500.\n";
    }
}
