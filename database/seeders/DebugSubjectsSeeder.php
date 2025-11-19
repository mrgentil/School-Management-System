<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class DebugSubjectsSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 DEBUG DES MATIÈRES DISPONIBLES...\n\n";
        
        $subjects = Subject::orderBy('name')->get();
        
        echo "📚 MATIÈRES DANS LA BASE DE DONNÉES (" . $subjects->count() . "):\n";
        foreach ($subjects as $subject) {
            echo "   ├─ {$subject->name} (ID: {$subject->id})\n";
        }
        
        echo "\n🎯 MATIÈRES ATTENDUES PAR TYPE:\n";
        
        $expectedSubjects = [
            'Technique' => ['Mathématiques', 'Physique', 'Électronique', 'Mécanique', 'Informatique', 'Français', 'Anglais'],
            'Commercial' => ['Mathématiques', 'Comptabilité', 'Économie', 'Gestion', 'Français', 'Anglais'],
            'Maternelle' => ['Jeux Éducatifs', 'Éveil', 'Motricité', 'Langage'],
            'Primaire' => ['Mathématiques', 'Français', 'Sciences', 'Histoire', 'Géographie', 'Anglais']
        ];
        
        foreach ($expectedSubjects as $type => $expectedList) {
            echo "\n📋 {$type}:\n";
            foreach ($expectedList as $expectedSubject) {
                $found = $subjects->where('name', $expectedSubject)->first();
                if ($found) {
                    echo "   ✅ {$expectedSubject} (trouvé)\n";
                } else {
                    echo "   ❌ {$expectedSubject} (manquant)\n";
                }
            }
        }
        
        echo "\n💡 SOLUTION TEMPORAIRE:\n";
        echo "Utilisons les matières existantes dans la base au lieu des noms attendus.\n";
        
        // Créer une correspondance basée sur les matières réelles
        $realSubjects = $subjects->pluck('name')->toArray();
        echo "\nMatières réelles: " . implode(', ', $realSubjects) . "\n";
    }
}
