<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaymentTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');
        
        // Types de frais scolaires
        $fees = [
            ['title' => 'Frais de scolarité', 'amount' => 150000, 'description' => 'Frais de scolarité annuels'],
            ['title' => 'Frais d\'inscription', 'amount' => 50000, 'description' => 'Frais d\'inscription annuels'],
            ['title' => 'Frais de bibliothèque', 'amount' => 25000, 'description' => 'Accès à la bibliothèque'],
            ['title' => 'Frais de laboratoire', 'amount' => 35000, 'description' => 'Accès aux laboratoires'],
            ['title' => 'Frais de sport', 'amount' => 15000, 'description' => 'Activités sportives'],
            ['title' => 'Frais de cantine', 'amount' => 100000, 'description' => 'Repas de midi pour l\'année'],
            ['title' => 'Frais d\'uniforme', 'amount' => 45000, 'description' => 'Uniforme scolaire'],
            ['title' => 'Frais d\'examen', 'amount' => 20000, 'description' => 'Frais d\'examen de fin d\'année'],
        ];

        foreach ($fees as $fee) {
            Payment::create([
                'title' => $fee['title'],
                'description' => $fee['description'],
                'amount' => $fee['amount'],
                'class_id' => null, // Pour tous les niveaux
                'year' => date('Y'),
                'ref_no' => 'PAY' . date('Ymd') . strtoupper(uniqid()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
