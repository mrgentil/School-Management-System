<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AssignmentSubmissionSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');
        $assignments = Assignment::all();
        $students = Student::all();
        
        foreach ($assignments as $assignment) {
            // Sélectionner un sous-ensemble d'étudiants pour soumettre ce devoir
            $submittingStudents = $students->random(rand(5, 15));
            
            foreach ($submittingStudents as $student) {
                $isSubmitted = $faker->boolean(80); // 80% de chance d'avoir soumis
                $isGraded = $isSubmitted && $faker->boolean(70); // 70% de chance d'être noté si soumis
                
                // Ne créer la soumission que si elle est soumise
                if ($isSubmitted) {
                    $submission = AssignmentSubmission::create([
                        'assignment_id' => $assignment->id,
                        'student_id' => $student->id,
                        'submission_text' => $faker->paragraph(3),
                        'file_path' => $faker->optional(0.3)->filePath(),
                        'submitted_at' => $faker->dateTimeBetween($assignment->created_at, 'now'),
                        'score' => $isGraded ? $faker->numberBetween(0, $assignment->max_score) : null,
                        'teacher_feedback' => $isGraded ? $faker->optional(0.7)->paragraph(2) : null,
                        'status' => $isGraded ? 'graded' : 'submitted',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
