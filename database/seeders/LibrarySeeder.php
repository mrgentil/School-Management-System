<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LibrarySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');
        
        // Créer 50 livres
        for ($i = 1; $i <= 50; $i++) {
            $quantity = $faker->numberBetween(1, 10);
            $available = $faker->numberBetween(0, $quantity);
            
            $book = Book::create([
                'name' => substr($faker->sentence(3), 0, 100), // Limiter à 100 caractères
                'author' => substr($faker->name, 0, 100), // Limiter à 100 caractères
                'description' => substr($faker->paragraph(3), 0, 255), // Limiter à 255 caractères
                'book_type' => $faker->randomElement(['Livre', 'Manuel', 'Revue', 'Thèse']),
                'url' => $faker->optional(0.3)->url,
                'location' => 'Rack ' . $faker->numberBetween(1, 20) . ', Étagère ' . $faker->randomLetter,
                'total_copies' => $quantity,
                'issued_copies' => $quantity - $available,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Générer des emprunts pour ce livre
            $issueCount = $faker->numberBetween(0, min(5, $book->issued_copies));
            
            for ($j = 0; $j < $issueCount; $j++) {
                $userType = $faker->randomElement(['student', 'teacher']);
                if ($userType === 'student') {
                    $userId = Student::inRandomOrder()->first()->user_id;
                } else {
                    // Récupérer un utilisateur avec le type 'teacher'
                    $teacher = User::where('user_type', 'teacher')->inRandomOrder()->first();
                    if (!$teacher) {
                        // Créer un enseignant si aucun n'existe
                        $teacher = User::create([
                            'name' => $faker->name,
                            'email' => $faker->unique()->safeEmail,
                            'password' => bcrypt('password'),
                            'user_type' => 'teacher',
                            'code' => 'T' . $faker->unique()->numberBetween(1000, 9999),
                            'gender' => $faker->randomElement(['male', 'female']),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    $userId = $teacher->id;
                }
                
                $requestDate = $faker->dateTimeBetween('-1 year', 'now');
                $expectedReturnDate = $faker->dateTimeBetween($requestDate, '+1 month');
                $isReturned = $faker->boolean(80); // 80% de chance que le livre soit retourné
                $actualReturnDate = $isReturned ? $faker->dateTimeBetween($requestDate, $expectedReturnDate) : null;
                
                // Vérifier si l'utilisateur est un étudiant
                $student = Student::where('user_id', $userId)->first();
                if (!$student) {
                    // Créer un enregistrement étudiant si nécessaire
                    $student = Student::create([
                        'user_id' => $userId,
                        'class_id' => $faker->numberBetween(1, 10), // Supposons qu'il y a des classes de 1 à 10
                        'admission_number' => 'STD' . $faker->unique()->numberBetween(1000, 9999),
                        'guardian_name' => $faker->name,
                        'guardian_phone' => $faker->phoneNumber,
                        'address' => $faker->address,
                        'date_of_birth' => $faker->dateTimeBetween('-20 years', '-15 years'),
                        'gender' => $faker->randomElement(['male', 'female']),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                BookRequest::create([
                    'book_id' => $book->id,
                    'student_id' => $userId, // Utiliser user_id comme clé étrangère
                    'request_date' => $requestDate->format('Y-m-d'),
                    'expected_return_date' => $expectedReturnDate->format('Y-m-d'),
                    'actual_return_date' => $actualReturnDate ? $actualReturnDate->format('Y-m-d') : null,
                    'status' => $isReturned ? 'returned' : 'borrowed',
                    'remarks' => $faker->optional(0.3)->sentence,
                    'approved_by' => 1, // ID de l'administrateur par défaut
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Mettre à jour le nombre de livres disponibles
                $book->decrement('available');
            }
        }
    }
}
