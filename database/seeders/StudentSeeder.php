<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\MyClass;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');
        $classes = MyClass::pluck('id')->toArray();
        
        // Créer 20 étudiants
        $existingCodes = [];
        
        for ($i = 0; $i < 20; $i++) {
            do {
                $code = 'STD' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            } while (in_array($code, $existingCodes));
            
            $existingCodes[] = $code;
            // Créer l'utilisateur
            $user = User::create([
                'code' => $code,
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'email' => 'etudiant' . ($i + 1) . '@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'student',
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'gender' => $faker->randomElement(['male', 'female']),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            // Créer l'étudiant
            // Obtenir un état et une LGA valides
            $state = \App\Models\State::inRandomOrder()->first();
            $lga = null;
            if ($state) {
                $lga = \App\Models\Lga::where('state_id', $state->id)->inRandomOrder()->first();
            }
            
            // Créer l'étudiant avec des valeurs par défaut si nécessaire
            Student::create([
                'user_id' => $user->id,
                'class_id' => $faker->randomElement($classes),
                'admission_number' => 'STD' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'guardian_name' => $faker->name,
                'guardian_phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'date_of_birth' => $faker->dateTimeBetween('-20 years', '-15 years'),
                'gender' => $faker->randomElement(['male', 'female']),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
