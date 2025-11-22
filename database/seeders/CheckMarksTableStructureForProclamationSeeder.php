<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckMarksTableStructureForProclamationSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 VÉRIFICATION DE LA STRUCTURE DE LA TABLE MARKS...\n\n";
        
        // Vérifier si la table marks existe
        if (!Schema::hasTable('marks')) {
            echo "❌ La table 'marks' n'existe pas!\n";
            return;
        }
        
        echo "✅ Table 'marks' trouvée\n\n";
        
        // Récupérer la structure de la table
        $columns = Schema::getColumnListing('marks');
        
        echo "📋 COLONNES ACTUELLES:\n";
        foreach ($columns as $column) {
            echo "   ├─ {$column}\n";
        }
        
        echo "\n🎯 COLONNES REQUISES POUR LE SYSTÈME DE PROCLAMATION:\n";
        
        $requiredColumns = [
            'id' => 'Identifiant unique',
            'student_id' => 'ID de l\'étudiant',
            'subject_id' => 'ID de la matière',
            'my_class_id' => 'ID de la classe',
            'exam_id' => 'ID de l\'examen',
            'mark_obtained' => 'Note obtenue (ou équivalent)',
            'year' => 'Année académique',
            'evaluation_type' => 'Type d\'évaluation (devoir, interrogation, etc.)',
            'max_points' => 'Cote maximale pour cette évaluation'
        ];
        
        echo "\n📊 VÉRIFICATION DES COLONNES:\n";
        
        foreach ($requiredColumns as $column => $description) {
            $exists = in_array($column, $columns);
            $status = $exists ? "✅" : "❌";
            echo "   {$status} {$column}: {$description}\n";
        }
        
        // Chercher des colonnes similaires
        echo "\n🔍 RECHERCHE DE COLONNES SIMILAIRES:\n";
        
        $markColumns = array_filter($columns, function($col) {
            return stripos($col, 'mark') !== false || 
                   stripos($col, 'note') !== false || 
                   stripos($col, 'score') !== false ||
                   stripos($col, 'point') !== false;
        });
        
        if (!empty($markColumns)) {
            echo "   📝 Colonnes liées aux notes trouvées:\n";
            foreach ($markColumns as $col) {
                echo "      ├─ {$col}\n";
            }
        }
        
        // Vérifier quelques enregistrements
        echo "\n📊 ÉCHANTILLON DE DONNÉES:\n";
        
        try {
            $sampleMarks = DB::table('marks')->limit(3)->get();
            
            if ($sampleMarks->count() > 0) {
                echo "   ├─ Nombre d'enregistrements: " . DB::table('marks')->count() . "\n";
                echo "   ├─ Échantillon:\n";
                
                foreach ($sampleMarks as $mark) {
                    echo "      ├─ ID: {$mark->id}\n";
                    
                    // Afficher les colonnes disponibles
                    foreach ((array)$mark as $key => $value) {
                        if (in_array($key, ['student_id', 'subject_id', 'my_class_id', 'exam_id'])) {
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
        
        echo "\n🎯 PROCHAINES ÉTAPES:\n\n";
        
        if (!in_array('evaluation_type', $columns)) {
            echo "1️⃣ AJOUTER LA COLONNE evaluation_type:\n";
            echo "   ├─ Type: ENUM('devoir', 'interrogation', 'interrogation_generale', 'examen')\n";
            echo "   ├─ Valeur par défaut: 'devoir'\n";
            echo "   └─ Permet de distinguer les types d'évaluations\n\n";
        }
        
        if (!in_array('max_points', $columns)) {
            echo "2️⃣ AJOUTER LA COLONNE max_points:\n";
            echo "   ├─ Type: DECIMAL(5,2) NULLABLE\n";
            echo "   ├─ Stocke la cote maximale spécifique\n";
            echo "   └─ Optionnel (utilise la config par défaut si NULL)\n\n";
        }
        
        echo "3️⃣ ADAPTER LA MIGRATION:\n";
        echo "   ├─ Utiliser la bonne colonne pour les notes\n";
        echo "   ├─ Vérifier la position d'insertion\n";
        echo "   └─ Tester sur une copie de la base\n\n";
        
        echo "4️⃣ METTRE À JOUR LE SERVICE DE CALCUL:\n";
        echo "   ├─ Adapter aux noms de colonnes réels\n";
        echo "   ├─ Gérer les différents formats de notes\n";
        echo "   └─ Tester les calculs\n\n";
        
        echo "🎉 ANALYSE TERMINÉE!\n";
        echo "Utilisez ces informations pour adapter la migration et le service.\n";
    }
}
