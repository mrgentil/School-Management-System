<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Subject;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AssignmentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');
        $subjects = Subject::pluck('id')->toArray();
        $classes = MyClass::pluck('id')->toArray();
        $teachers = User::where('user_type', 'teacher')->pluck('id')->toArray();
        
        // Créer 30 devoirs
        for ($i = 0; $i < 30; $i++) {
            $classId = $faker->randomElement($classes);
            $sections = Section::where('my_class_id', $classId)->pluck('id')->toArray();
            
            Assignment::create([
                'title' => $faker->sentence(4),
                'description' => $faker->paragraph(3),
                'my_class_id' => $classId,
                'section_id' => $faker->randomElement($sections),
                'subject_id' => $faker->randomElement($subjects),
                'teacher_id' => $faker->randomElement($teachers),
                'file_path' => $faker->boolean(30) ? 'assignments/example.pdf' : null,
                'max_score' => $faker->numberBetween(10, 20),
                'due_date' => $faker->dateTimeBetween('now', '+1 month'),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
