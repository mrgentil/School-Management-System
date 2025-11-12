<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\MyClass;
use App\Models\Payment;
use App\Models\PaymentRecord;
use App\Models\Receipt;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\StudyMaterial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');

        // Créer des classes si elles n'existent pas
        $classes = MyClass::all();
        if ($classes->isEmpty()) {
            $classes = collect([
                MyClass::create(['name' => 'L1 Informatique', 'class_type_id' => 1]),
                MyClass::create(['name' => 'L2 Informatique', 'class_type_id' => 1]),
                MyClass::create(['name' => 'L3 Informatique', 'class_type_id' => 1]),
                MyClass::create(['name' => 'M1 Informatique', 'class_type_id' => 1]),
                MyClass::create(['name' => 'M2 Informatique', 'class_type_id' => 1]),
            ]);
        }

        // Créer des matières si elles n'existent pas
        $subjects = Subject::all();
        if ($subjects->isEmpty()) {
            $subjects = collect([
                Subject::create(['name' => 'Programmation Web', 'my_class_id' => $classes->random()->id, 'teacher_id' => 1]),
                Subject::create(['name' => 'Base de Données', 'my_class_id' => $classes->random()->id, 'teacher_id' => 1]),
                Subject::create(['name' => 'Algorithmique', 'my_class_id' => $classes->random()->id, 'teacher_id' => 1]),
                Subject::create(['name' => 'Réseaux', 'my_class_id' => $classes->random()->id, 'teacher_id' => 1]),
                Subject::create(['name' => 'Systèmes d\'Exploitation', 'my_class_id' => $classes->random()->id, 'teacher_id' => 1]),
            ]);
        }

        // Créer des paiements si ils n'existent pas
        $payments = Payment::all();
        if ($payments->isEmpty()) {
            $payments = collect([
                Payment::create(['title' => 'Frais de Scolarité', 'amount' => 500000, 'year' => 2024]),
                Payment::create(['title' => 'Frais d\'Inscription', 'amount' => 25000, 'year' => 2024]),
                Payment::create(['title' => 'Frais de Bibliothèque', 'amount' => 15000, 'year' => 2024]),
                Payment::create(['title' => 'Frais de Laboratoire', 'amount' => 30000, 'year' => 2024]),
            ]);
        }

        // Créer 20 étudiants avec comptes complets
        $students = collect();
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'code' => 'STD' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'email' => 'etudiant' . $i . '@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'student',
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'gender' => $faker->randomElement(['male', 'female']),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'class_id' => $classes->random()->id,
                'admission_number' => 'STD' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'guardian_name' => $faker->name,
                'guardian_phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'date_of_birth' => $faker->dateTimeBetween('-22 years', '-18 years'),
                'gender' => $faker->randomElement(['male', 'female']),
            ]);

            $students->push($student);
        }

        // Générer des données financières pour chaque étudiant
        foreach ($students as $student) {
            foreach ($payments as $payment) {
                $amount = $payment->amount;
                $paidAmount = $faker->numberBetween(0, $amount);
                $balance = $amount - $paidAmount;

                $paymentRecord = PaymentRecord::create([
                    'payment_id' => $payment->id,
                    'student_id' => $student->id,
                    'ref_no' => 'PAY' . time() . $student->id . $payment->id,
                    'amt_paid' => $paidAmount,
                    'balance' => $balance,
                    'paid' => $balance <= 0 ? 1 : 0,
                    'year' => 2024,
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                ]);

                // Créer un reçu si paiement effectué
                if ($paidAmount > 0) {
                    Receipt::create([
                        'pr_id' => $paymentRecord->id,
                        'amt_paid' => $paidAmount,
                        'balance' => $balance,
                        'year' => 2024,
                        'status' => $faker->randomElement(['approved', 'pending', 'approved']),
                        'payment_method' => $faker->randomElement(['cash', 'bank_transfer', 'online']),
                    ]);
                }
            }
        }

        // Créer des livres
        $books = collect();
        $bookTitles = [
            'PHP pour les Nuls', 'JavaScript Avancé', 'Laravel Framework', 'MySQL Guide Complet',
            'Algorithmique et Structures de Données', 'Réseaux Informatiques', 'Sécurité Informatique',
            'Intelligence Artificielle', 'Big Data', 'Cloud Computing', 'DevOps Essentials',
            'Python pour Data Science', 'Machine Learning', 'Cybersecurity Fundamentals',
            'Base de Données NoSQL', 'React.js Guide', 'Node.js Développement', 'Docker Containerization'
        ];

        foreach ($bookTitles as $index => $title) {
            $quantity = $faker->numberBetween(3, 15);
            $available = $faker->numberBetween(0, $quantity);

            $book = Book::create([
                'name' => $title,
                'author' => $faker->name,
                'description' => $faker->paragraph(2),
                'book_type' => $faker->randomElement(['Livre', 'Manuel', 'Revue']),
                'location' => 'Rack ' . $faker->numberBetween(1, 10) . ', Ét ' . $faker->numberBetween(1, 5),
                'total_copies' => $quantity,
                'issued_copies' => $quantity - $available,
                'available' => $available,
            ]);

            $books->push($book);
        }

        // Générer des demandes de livres
        foreach ($students as $student) {
            $bookCount = $faker->numberBetween(1, 5);
            $selectedBooks = $books->random(min($bookCount, $books->count()));

            foreach ($selectedBooks as $book) {
                if ($book->available > 0) {
                    $status = $faker->randomElement(['pending', 'approved', 'borrowed', 'returned', 'rejected']);

                    BookRequest::create([
                        'book_id' => $book->id,
                        'student_id' => $student->user_id,
                        'status' => $status,
                        'request_date' => $faker->dateTimeBetween('-3 months', 'now'),
                        'expected_return_date' => $status === 'borrowed' ? $faker->dateTimeBetween('now', '+2 weeks') : null,
                        'actual_return_date' => in_array($status, ['returned']) ? $faker->dateTimeBetween('-1 week', 'now') : null,
                        'remarks' => $faker->optional(0.3)->sentence,
                        'approved_by' => $status !== 'pending' ? 1 : null,
                    ]);

                    // Mettre à jour la disponibilité
                    if (in_array($status, ['approved', 'borrowed'])) {
                        $book->decrement('available');
                    }
                }
            }
        }

        // Créer des devoirs
        $assignments = collect();
        for ($i = 1; $i <= 15; $i++) {
            $assignment = Assignment::create([
                'title' => 'Devoir ' . $i . ' - ' . $faker->sentence(3),
                'description' => $faker->paragraph(3),
                'my_class_id' => $classes->random()->id,
                'subject_id' => $subjects->random()->id,
                'teacher_id' => 1,
                'max_score' => $faker->numberBetween(10, 20),
                'due_date' => $faker->dateTimeBetween('now', '+2 weeks'),
                'status' => 'active',
            ]);

            $assignments->push($assignment);
        }

        // Générer des soumissions de devoirs
        foreach ($students as $student) {
            $submissionCount = $faker->numberBetween(3, 8);
            $selectedAssignments = $assignments->random(min($submissionCount, $assignments->count()));

            foreach ($selectedAssignments as $assignment) {
                $submitted = $faker->boolean(80); // 80% de chance d'avoir soumis
                $score = $submitted ? $faker->numberBetween(0, $assignment->max_score) : null;

                AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->user_id,
                    'submitted_at' => $submitted ? $faker->dateTimeBetween('-2 weeks', 'now') : null,
                    'file_path' => $submitted ? 'assignments/submission_' . $student->id . '_' . $assignment->id . '.pdf' : null,
                    'score' => $score,
                    'remarks' => $score !== null ? $faker->sentence : null,
                    'status' => $submitted ? 'submitted' : 'pending',
                ]);
            }
        }

        // Générer des présences sur 30 jours
        for ($day = 30; $day >= 0; $day--) {
            $date = now()->subDays($day);

            // Sauter les week-ends
            if ($date->isWeekend()) continue;

            foreach ($students as $student) {
                $subject = $subjects->random();

                // 85% de présence, 10% d'absence, 5% de retard
                $attendanceType = $faker->randomElement([
                    'present', 'present', 'present', 'present', 'present',
                    'present', 'present', 'present', 'present', 'present',
                    'present', 'present', 'present', 'present', 'present',
                    'present', 'present', 'late', 'absent'
                ]);

                $justified = false;
                if ($attendanceType === 'absent' && $faker->boolean(60)) {
                    $attendanceType = 'absent_justified';
                    $justified = true;
                } elseif ($attendanceType === 'late' && $faker->boolean(40)) {
                    $attendanceType = 'late_justified';
                    $justified = true;
                }

                Attendance::create([
                    'student_id' => $student->id,
                    'class_id' => $student->class_id,
                    'subject_id' => $subject->id,
                    'date' => $date->format('Y-m-d'),
                    'time' => '08:00:00',
                    'end_time' => '10:00:00',
                    'status' => $attendanceType,
                    'notes' => $justified ? $faker->sentence : null,
                ]);
            }
        }

        // Créer des matériels pédagogiques
        $materials = collect();
        $materialTitles = [
            'Cours PHP Avancé', 'TP Base de Données', 'Exercices Algorithmique',
            'Guide Réseaux', 'Support Systèmes d\'Exploitation', 'TP JavaScript',
            'Cours Laravel', 'Exercices Python', 'Guide Sécurité', 'TP Machine Learning'
        ];

        foreach ($materialTitles as $title) {
            StudyMaterial::create([
                'title' => $title,
                'description' => $faker->paragraph(2),
                'subject_id' => $subjects->random()->id,
                'class_id' => $classes->random()->id,
                'file_path' => 'materials/' . Str::slug($title) . '.pdf',
                'file_type' => 'pdf',
                'uploaded_by' => 1,
                'is_active' => true,
            ]);
        }

        $this->command->info('Données de test créées avec succès !');
        $this->command->info('Comptes étudiants : etudiant1@example.com à etudiant20@example.com');
        $this->command->info('Mot de passe : password');
    }
}
