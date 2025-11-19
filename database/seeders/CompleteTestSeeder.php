<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentRecord;
use App\Models\Section;
use App\Models\MyClass;
use App\Models\User;
use App\Models\Student;
use App\Models\Mark;
use App\Models\ExamRecord;

class CompleteTestSeeder extends Seeder
{
    public function run(): void
    {
        echo "🎯 CRÉATION COMPLÈTE DE DONNÉES DE TEST...\n\n";
        
        // 1. Nettoyer les données existantes
        echo "1. Nettoyage des données existantes...\n";
        Mark::where('my_class_id', 6)->delete();
        ExamRecord::where('my_class_id', 6)->delete();
        StudentRecord::where('my_class_id', 6)->delete();
        
        // 2. Créer des utilisateurs étudiants
        echo "2. Création d'utilisateurs étudiants...\n";
        $students = [];
        $names = [
            'Alice Martin',
            'Bob Dupont', 
            'Clara Mbala',
            'David Tshala',
            'Emma Kabila'
        ];
        
        foreach ($names as $index => $name) {
            // Créer l'utilisateur avec un code unique
            $uniqueCode = 'TEST' . str_pad($index + 1, 3, '0', STR_PAD_LEFT) . rand(100, 999);
            $user = User::create([
                'name' => $name,
                'email' => 'teststudent' . ($index + 1) . rand(100, 999) . '@test.com',
                'user_type' => 'student',
                'code' => $uniqueCode,
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
            
            // Créer le profil étudiant
            Student::create([
                'user_id' => $user->id,
                'admission_number' => 'ADM' . str_pad($index + 1, 4, '0', STR_PAD_LEFT)
            ]);
            
            $students[] = $user;
            echo "   ✅ Créé: {$name} (ID: {$user->id})\n";
        }
        
        // 3. Répartir dans les sections
        echo "\n3. Répartition dans les sections...\n";
        $sections = Section::where('my_class_id', 6)->get();
        
        foreach ($students as $index => $user) {
            $section = $sections[$index % $sections->count()];
            
            StudentRecord::create([
                'user_id' => $user->id,
                'my_class_id' => 6,
                'section_id' => $section->id,
                'session' => '2025-2026',
                'adm_no' => 'ADM' . str_pad($index + 1, 4, '0', STR_PAD_LEFT)
            ]);
            
            echo "   ✅ {$user->name} → Section {$section->name} (ID: {$section->id})\n";
        }
        
        // 4. Vérification finale
        echo "\n4. VÉRIFICATION FINALE:\n";
        foreach ($sections as $section) {
            $count = StudentRecord::where('my_class_id', 6)
                ->where('section_id', $section->id)
                ->with('user')
                ->get();
            
            echo "   📋 Section {$section->name} (ID: {$section->id}): {$count->count()} étudiants\n";
            foreach ($count as $sr) {
                echo "      - {$sr->user->name}\n";
            }
        }
        
        echo "\n🎉 DONNÉES DE TEST CRÉÉES AVEC SUCCÈS!\n";
        echo "💡 Maintenant teste avec la classe 'Maternelle 3ème Année' et n'importe quelle section.\n";
    }
}
