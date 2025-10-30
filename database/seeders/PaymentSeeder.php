<?php

namespace Database\Seeders;

use App\Models\PaymentRecord;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');
        $students = Student::with('user')->get();
        $payments = Payment::all();
        
        foreach ($students as $student) {
            // Chaque étudiant aura entre 1 et 5 paiements
            $paymentCount = $faker->numberBetween(1, 5);
            
            for ($i = 0; $i < $paymentCount; $i++) {
                $payment = $payments->random();
                $amount = $payment->amount;
                $amtPaid = $faker->numberBetween(0, $amount);
                $balance = $amount - $amtPaid;
                
                // Créer l'enregistrement de paiement
                $paymentRecord = PaymentRecord::create([
                    'payment_id' => $payment->id,
                    'student_id' => $student->id,
                    'ref_no' => 'PAY' . time() . $student->id . $i,
                    'amt_paid' => $amtPaid,
                    'balance' => $balance,
                    'paid' => $balance <= 0 ? 1 : 0,
                    'year' => $faker->numberBetween(2022, 2025),
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => now(),
                ]);
                
                // Créer un reçu si un paiement a été effectué
                if ($amtPaid > 0) {
                    Receipt::create([
                        'pr_id' => $paymentRecord->id,
                        'amt_paid' => $amtPaid,
                        'balance' => $balance,
                        'year' => $paymentRecord->year,
                        'created_at' => $paymentRecord->created_at,
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
