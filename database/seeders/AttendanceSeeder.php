<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\MyClass;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');
        $students = Student::all();
        $subjects = Subject::all();
        $classes = MyClass::all();
        
        // Générer des présences pour les 60 derniers jours
        for ($day = 60; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);
            
            // Ne pas générer de données pour les week-ends
            if ($date->isWeekend()) {
                continue;
            }
            
            // Pour chaque classe
            foreach ($classes as $class) {
                $classStudents = $students->where('my_class_id', $class->id);
                
                // Pour chaque matière de la classe
                $classSubjects = $subjects->where('my_class_id', $class->id);
                
                foreach ($classSubjects as $subject) {
                    // Générer 1 à 3 sessions par jour par matière
                    $sessionCount = $faker->numberBetween(1, 3);
                    
                    for ($session = 1; $session <= $sessionCount; $session++) {
                        // Heure de début aléatoire entre 8h et 16h
                        $startTime = $date->copy()->setTime(8 + $faker->numberBetween(0, 7), $faker->randomElement([0, 15, 30, 45]));
                        $endTime = $startTime->copy()->addMinutes($faker->numberBetween(45, 120));
                        
                        // Pour chaque étudiant de la classe
                        foreach ($classStudents as $student) {
                            // 5% de chance d'être absent sans justification
                            $status = $faker->randomElement([
                                'present', 'present', 'present', 'present', 'present',
                                'present', 'present', 'present', 'present', 'present',
                                'present', 'present', 'present', 'present', 'present',
                                'present', 'present', 'present', 'present', 'absent'
                            ]);
                            
                            // 10% de chance d'avoir un retard
                            if ($status === 'present' && $faker->boolean(10)) {
                                $status = 'late';
                            }
                            
                            // 5% de chance d'être en retard avec justification
                            if ($status === 'late' && $faker->boolean(50)) {
                                $status = 'late_justified';
                            }
                            
                            // 5% de chance d'être absent avec justification
                            if ($status === 'absent' && $faker->boolean(50)) {
                                $status = 'absent_justified';
                            }
                            
                            Attendance::create([
                                'student_id' => $student->id,
                                'class_id' => $class->id,
                                'subject_id' => $subject->id,
                                'date' => $date->format('Y-m-d'),
                                'time' => $startTime->format('H:i:s'),
                                'end_time' => $endTime->format('H:i:s'),
                                'status' => $status,
                                'notes' => $status !== 'present' ? $faker->optional(0.7)->sentence : null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
    }
}
