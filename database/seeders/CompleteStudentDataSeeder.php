<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentRecord;
use App\Models\Mark;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SubjectGradeConfig;
use App\Models\Exam;
use App\Helpers\Qs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompleteStudentDataSeeder extends Seeder
{
    protected $year;
    
    // Prénoms congolais/africains
    protected $firstNames = [
        'male' => ['Jean', 'Pierre', 'Paul', 'Joseph', 'Emmanuel', 'David', 'Samuel', 'Daniel', 'Patrick', 'Christian', 
                   'Blaise', 'Cédric', 'Fiston', 'Gloire', 'Héritier', 'Junior', 'Kevin', 'Luc', 'Marc', 'Nathan',
                   'Olivier', 'Prince', 'Rodrigue', 'Steve', 'Thierry', 'Victor', 'William', 'Xavier', 'Yves', 'Zacharie'],
        'female' => ['Marie', 'Grace', 'Esther', 'Ruth', 'Sarah', 'Rebecca', 'Rachel', 'Deborah', 'Naomi', 'Judith',
                     'Béatrice', 'Carole', 'Divine', 'Élodie', 'Fleur', 'Gracia', 'Henriette', 'Irène', 'Joséphine', 'Kelly',
                     'Linda', 'Michelle', 'Noëlla', 'Ornella', 'Prisca', 'Queen', 'Rose', 'Sylvie', 'Thérèse', 'Vanessa']
    ];
    
    // Noms de famille congolais
    protected $lastNames = [
        'Mbala', 'Tshimanga', 'Kabongo', 'Mutombo', 'Kalala', 'Kasongo', 'Mbuyi', 'Nkulu', 'Ilunga', 'Ngoy',
        'Kapinga', 'Mwamba', 'Bakala', 'Mukendi', 'Tshilombo', 'Kalonji', 'Mwenze', 'Kabamba', 'Ntumba', 'Kabuya',
        'Lumumba', 'Mobutu', 'Tshisekedi', 'Katumbi', 'Bemba', 'Kamerhe', 'Fayulu', 'Mukwege', 'Mende', 'Shadary',
        'Mulunda', 'Kazadi', 'Tshibanda', 'Mujinga', 'Kalombo', 'Mwema', 'Kayembe', 'Bukasa', 'Lubamba', 'Ngandu'
    ];

    public function run()
    {
        $this->year = Qs::getSetting('current_session') ?: '2025-2026';
        
        $this->command->info("=== Création des données de test complètes ===");
        $this->command->info("Année scolaire: {$this->year}");
        
        // Récupérer les classes existantes
        $classes = MyClass::with('section')->get();
        
        if ($classes->isEmpty()) {
            $this->command->error("Aucune classe trouvée. Veuillez d'abord créer des classes.");
            return;
        }
        
        $this->command->info("Classes trouvées: " . $classes->count());
        
        foreach ($classes as $class) {
            $this->command->info("  - {$class->name} (ID: {$class->id})");
        }
        
        // Créer un enseignant si nécessaire
        $teacher = $this->createTeacher();
        
        // Pour chaque classe
        foreach ($classes as $class) {
            $this->command->newLine();
            $this->command->info("=== Traitement de la classe: {$class->name} ===");
            
            // Créer ou récupérer la section par défaut
            $section = $class->section->first();
            if (!$section) {
                $section = Section::create([
                    'name' => 'A',
                    'my_class_id' => $class->id
                ]);
                $this->command->info("Section créée: A");
            }
            
            // Créer les matières pour cette classe
            $subjects = $this->createSubjectsForClass($class, $teacher);
            
            // Créer la configuration des notes
            $this->createGradeConfigs($class, $subjects);
            
            // Créer 15 étudiants par classe
            $students = $this->createStudentsForClass($class, $section, 15);
            
            // Créer les devoirs pour toutes les périodes
            $assignments = $this->createAssignments($class, $section, $subjects, $teacher);
            
            // Créer les soumissions avec notes
            $this->createSubmissionsWithGrades($assignments, $students);
            
            // Créer les notes d'interrogations (t1-t4) et TCA
            $this->createMarksForStudents($class, $section, $subjects, $students);
            
            // Créer les examens et notes d'examens
            $this->createExamsAndGrades($class, $section, $subjects, $students);
        }
        
        $this->command->newLine();
        $this->command->info("=== Données de test créées avec succès! ===");
        $this->printSummary();
    }
    
    protected function createTeacher()
    {
        $teacher = User::where('user_type', 'teacher')->first();
        
        if (!$teacher) {
            $teacher = User::create([
                'name' => 'Prof. Jean-Marie Kabongo',
                'email' => 'prof.kabongo@eschool.cd',
                'username' => 'prof_kabongo',
                'password' => Hash::make('password123'),
                'user_type' => 'teacher',
                'gender' => 'Male',
                'code' => 'TCH' . rand(1000, 9999)
            ]);
            $this->command->info("Enseignant créé: {$teacher->name}");
        }
        
        return $teacher;
    }
    
    protected function createSubjectsForClass($class, $teacher)
    {
        $subjectNames = [
            ['name' => 'Mathématiques', 'slug' => 'MATH'],
            ['name' => 'Français', 'slug' => 'FRA'],
            ['name' => 'Anglais', 'slug' => 'ANG'],
            ['name' => 'Physique', 'slug' => 'PHY'],
            ['name' => 'Chimie', 'slug' => 'CHI'],
            ['name' => 'Informatique', 'slug' => 'INFO'],
            ['name' => 'Histoire', 'slug' => 'HIST'],
            ['name' => 'Géographie', 'slug' => 'GEO']
        ];
        
        $subjects = [];
        
        foreach ($subjectNames as $subjectData) {
            $subject = Subject::firstOrCreate(
                [
                    'my_class_id' => $class->id,
                    'name' => $subjectData['name']
                ],
                [
                    'slug' => $subjectData['slug'],
                    'teacher_id' => $teacher->id
                ]
            );
            $subjects[] = $subject;
        }
        
        $this->command->info("  Matières: " . count($subjects) . " créées/vérifiées");
        
        return $subjects;
    }
    
    protected function createGradeConfigs($class, $subjects)
    {
        foreach ($subjects as $subject) {
            // Varier les cotes selon la matière
            $periodMax = in_array($subject->name, ['Mathématiques', 'Français']) ? 40 : 20;
            $examMax = in_array($subject->name, ['Mathématiques', 'Français']) ? 80 : 40;
            
            SubjectGradeConfig::updateOrCreate(
                [
                    'my_class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'academic_year' => $this->year
                ],
                [
                    'period_max_points' => $periodMax,
                    'exam_max_points' => $examMax,
                    'active' => true
                ]
            );
        }
        
        $this->command->info("  Configurations de notes: " . count($subjects) . " créées");
    }
    
    protected function createStudentsForClass($class, $section, $count)
    {
        $students = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $gender = rand(0, 1) ? 'Male' : 'Female';
            $genderKey = $gender === 'Male' ? 'male' : 'female';
            
            $firstName = $this->firstNames[$genderKey][array_rand($this->firstNames[$genderKey])];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $name = "$firstName $lastName";
            
            // Vérifier si l'étudiant existe déjà
            $email = strtolower(Str::slug($firstName)) . '.' . strtolower($lastName) . $i . '@student.eschool.cd';
            $existingUser = User::where('email', $email)->first();
            
            if ($existingUser) {
                $students[] = $existingUser;
                continue;
            }
            
            $admNo = 'STU' . $class->id . str_pad($i, 3, '0', STR_PAD_LEFT);
            
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'username' => strtolower($firstName) . '_' . strtolower($lastName) . $i,
                'password' => Hash::make('password123'),
                'user_type' => 'student',
                'gender' => $gender,
                'code' => $admNo,
                'dob' => now()->subYears(rand(15, 20))->format('Y-m-d')
            ]);
            
            StudentRecord::create([
                'user_id' => $user->id,
                'my_class_id' => $class->id,
                'section_id' => $section->id,
                'session' => $this->year,
                'adm_no' => $admNo,
                'year_admitted' => now()->year
            ]);
            
            $students[] = $user;
        }
        
        $this->command->info("  Étudiants: " . count($students) . " créés/vérifiés");
        
        return $students;
    }
    
    protected function createAssignments($class, $section, $subjects, $teacher)
    {
        $assignments = [];
        $assignmentTypes = [
            ['title' => 'Devoir Surveillé', 'max' => 20],
            ['title' => 'Travail Pratique', 'max' => 10],
            ['title' => 'Exercices', 'max' => 10]
        ];
        
        // Créer des devoirs pour les 4 périodes
        foreach ([1, 2, 3, 4] as $period) {
            foreach ($subjects as $subject) {
                // 2-3 devoirs par matière par période
                $numAssignments = rand(2, 3);
                
                for ($i = 1; $i <= $numAssignments; $i++) {
                    $type = $assignmentTypes[array_rand($assignmentTypes)];
                    
                    $assignment = Assignment::firstOrCreate(
                        [
                            'my_class_id' => $class->id,
                            'subject_id' => $subject->id,
                            'period' => $period,
                            'title' => "{$type['title']} $i - {$subject->name} P$period"
                        ],
                        [
                            'section_id' => $section->id,
                            'teacher_id' => $teacher->id,
                            'description' => "Devoir de {$subject->name} pour la période $period",
                            'due_date' => now()->addDays(rand(-30, 30)),
                            'max_score' => $type['max'],
                            'status' => 'active',
                            'year' => $this->year
                        ]
                    );
                    
                    $assignments[] = $assignment;
                }
            }
        }
        
        $this->command->info("  Devoirs: " . count($assignments) . " créés");
        
        return $assignments;
    }
    
    protected function createSubmissionsWithGrades($assignments, $students)
    {
        $count = 0;
        
        foreach ($assignments as $assignment) {
            foreach ($students as $student) {
                // 90% des étudiants soumettent
                if (rand(1, 100) <= 90) {
                    // Générer une note réaliste avec distribution normale
                    $baseScore = $this->generateRealisticScore($assignment->max_score);
                    
                    AssignmentSubmission::firstOrCreate(
                        [
                            'assignment_id' => $assignment->id,
                            'student_id' => $student->id
                        ],
                        [
                            'submission_text' => 'Travail soumis par ' . $student->name,
                            'submitted_at' => now()->subDays(rand(1, 10)),
                            'score' => $baseScore,
                            'status' => 'graded',
                            'teacher_feedback' => $this->getRandomFeedback($baseScore, $assignment->max_score)
                        ]
                    );
                    $count++;
                }
            }
        }
        
        $this->command->info("  Soumissions: $count créées");
    }
    
    protected function createMarksForStudents($class, $section, $subjects, $students)
    {
        $count = 0;
        
        foreach ($subjects as $subject) {
            $config = SubjectGradeConfig::getConfig($class->id, $subject->id, $this->year);
            $maxPoints = $config ? $config->period_max_points : 20;
            
            foreach ($students as $student) {
                // Créer ou mettre à jour les notes
                $mark = Mark::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'my_class_id' => $class->id,
                        'year' => $this->year
                    ],
                    [
                        'section_id' => $section->id
                    ]
                );
                
                // Notes d'interrogations par période (t1, t2, t3, t4)
                // Générer des notes cohérentes pour le même étudiant
                $studentLevel = rand(40, 100) / 100; // Niveau de base de l'étudiant (40% à 100%)
                
                $mark->t1 = round($this->generateStudentScore($maxPoints, $studentLevel), 2);
                $mark->t2 = round($this->generateStudentScore($maxPoints, $studentLevel), 2);
                $mark->t3 = round($this->generateStudentScore($maxPoints, $studentLevel), 2);
                $mark->t4 = round($this->generateStudentScore($maxPoints, $studentLevel), 2);
                
                // TCA (Test Continu Accumulé) - moyenne des 4 périodes / 4 * 2
                $mark->tca = round(($mark->t1 + $mark->t2 + $mark->t3 + $mark->t4) / 4, 2);
                
                $mark->save();
                $count++;
            }
        }
        
        $this->command->info("  Notes d'interrogations: $count créées");
    }
    
    protected function createExamsAndGrades($class, $section, $subjects, $students)
    {
        // Créer les examens de semestre
        $exam1 = Exam::firstOrCreate(
            ['semester' => 1, 'year' => $this->year],
            ['name' => 'Examen Semestriel 1', 'status' => 'completed']
        );
        
        $exam2 = Exam::firstOrCreate(
            ['semester' => 2, 'year' => $this->year],
            ['name' => 'Examen Semestriel 2', 'status' => 'active']
        );
        
        $this->command->info("  Examens: 2 créés (S1 et S2)");
        
        // Ajouter les notes d'examens
        $count = 0;
        foreach ($subjects as $subject) {
            $config = SubjectGradeConfig::getConfig($class->id, $subject->id, $this->year);
            $maxExamPoints = $config ? $config->exam_max_points : 40;
            
            foreach ($students as $student) {
                $mark = Mark::where([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'my_class_id' => $class->id,
                    'year' => $this->year
                ])->first();
                
                if ($mark) {
                    // Niveau cohérent basé sur les notes de période
                    $avgPeriod = ($mark->t1 + $mark->t2) / 2;
                    $periodMax = $config ? $config->period_max_points : 20;
                    $studentLevel = $avgPeriod / $periodMax;
                    
                    // Notes d'examens
                    $mark->s1_exam = round($this->generateStudentScore($maxExamPoints, $studentLevel), 2);
                    
                    // S2 - seulement pour certains (examen pas encore terminé)
                    if (rand(1, 100) <= 30) {
                        $avgPeriod2 = ($mark->t3 + $mark->t4) / 2;
                        $studentLevel2 = $avgPeriod2 / $periodMax;
                        $mark->s2_exam = round($this->generateStudentScore($maxExamPoints, $studentLevel2), 2);
                    }
                    
                    // Mettre à jour exam_id pour les relations
                    $mark->exam_id = $exam1->id;
                    $mark->exm = $mark->s1_exam; // Colonne legacy
                    
                    $mark->save();
                    $count++;
                }
            }
        }
        
        $this->command->info("  Notes d'examens: $count créées");
    }
    
    protected function generateRealisticScore($maxScore)
    {
        // Distribution normale centrée sur 70% avec écart-type de 15%
        $mean = 0.70;
        $stdDev = 0.15;
        
        // Box-Muller transform pour distribution normale
        $u1 = rand() / getrandmax();
        $u2 = rand() / getrandmax();
        $z = sqrt(-2 * log($u1)) * cos(2 * pi() * $u2);
        
        $score = $mean + $stdDev * $z;
        $score = max(0.2, min(1, $score)); // Entre 20% et 100%
        
        return round($score * $maxScore, 2);
    }
    
    protected function generateStudentScore($maxScore, $baseLevel)
    {
        // Variation de ±15% autour du niveau de base de l'étudiant
        $variation = (rand(-15, 15) / 100);
        $score = $baseLevel + $variation;
        $score = max(0.2, min(1, $score));
        
        return round($score * $maxScore, 2);
    }
    
    protected function getRandomFeedback($score, $maxScore)
    {
        $percentage = ($score / $maxScore) * 100;
        
        if ($percentage >= 80) {
            $feedbacks = ['Excellent travail!', 'Très bien, continue ainsi!', 'Félicitations!', 'Travail remarquable!'];
        } elseif ($percentage >= 60) {
            $feedbacks = ['Bon travail.', 'Bien, mais peut mieux faire.', 'Satisfaisant.', 'Correct.'];
        } elseif ($percentage >= 50) {
            $feedbacks = ['Passable.', 'Peut mieux faire.', 'Travail acceptable.', 'À améliorer.'];
        } else {
            $feedbacks = ['Insuffisant, il faut travailler davantage.', 'Doit faire plus d\'efforts.', 'Travail à revoir.'];
        }
        
        return $feedbacks[array_rand($feedbacks)];
    }
    
    protected function printSummary()
    {
        $this->command->newLine();
        $this->command->info("=== RÉSUMÉ DES DONNÉES CRÉÉES ===");
        $this->command->table(
            ['Élément', 'Nombre'],
            [
                ['Classes', MyClass::count()],
                ['Sections', Section::count()],
                ['Étudiants', User::where('user_type', 'student')->count()],
                ['Matières', Subject::count()],
                ['Devoirs', Assignment::count()],
                ['Soumissions', AssignmentSubmission::count()],
                ['Notes (marks)', Mark::count()],
                ['Examens', Exam::count()],
                ['Configs notes', SubjectGradeConfig::count()]
            ]
        );
        
        $this->command->newLine();
        $this->command->info("Vous pouvez maintenant:");
        $this->command->info("1. Aller sur la Feuille de Tabulation pour voir les notes");
        $this->command->info("2. Générer des bulletins PDF pour les étudiants");
        $this->command->info("3. Consulter les proclamations");
    }
}
