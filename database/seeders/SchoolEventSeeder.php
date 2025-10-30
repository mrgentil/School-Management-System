<?php

namespace Database\Seeders;

use App\Models\SchoolEvent;
use App\Models\MyClass;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SchoolEventSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');
        $classes = MyClass::all();
        $subjects = Subject::all();
        
        // Types d'événements
        $eventTypes = [
            'exam' => 'Examen',
            'holiday' => 'Vacances',
            'meeting' => 'Réunion',
            'event' => 'Événement',
            'assignment_due' => 'Rendu de devoir',
            'test' => 'Contrôle',
        ];
        
        // Couleurs pour les événements
        $colors = [
            '#3c8dbc', // Bleu
            '#f39c12', // Orange
            '#00a65a', // Vert
            '#dd4b39', // Rouge
            '#605ca8', // Violet
            '#ff851b', // Orange foncé
        ];
        
        // Générer des événements pour les 6 prochains mois
        for ($i = 0; $i < 60; $i++) {
            $startDate = Carbon::now()->addDays(rand(-30, 90));
            $isAllDay = $faker->boolean(30);
            
            if ($isAllDay) {
                $endDate = (clone $startDate)->addDays(rand(1, 7));
                $startTime = null;
                $endTime = null;
            } else {
                $startTime = $startDate->copy()->setTime(8 + rand(0, 8), $faker->randomElement([0, 15, 30, 45]));
                $endTime = (clone $startTime)->addHours(rand(1, 3));
                $endDate = $startDate;
            }
            
            $eventType = $faker->randomElement(array_keys($eventTypes));
            $classId = $faker->boolean(70) ? $classes->random()->id : null;
            $subjectId = $faker->boolean(60) ? $subjects->random()->id : null;
            
            $event = SchoolEvent::create([
                'title' => $this->generateEventTitle($eventType, $faker, $classId, $subjectId, $classes, $subjects),
                'description' => $faker->optional(0.8)->paragraph(3),
                'event_date' => $startDate->format('Y-m-d'),
                'start_time' => $startTime ? $startTime->format('H:i:s') : null,
                'end_time' => $endTime ? $endTime->format('H:i:s') : null,
                'location' => $faker->optional(0.6)->city,
                'event_type' => $this->mapEventType($eventType),
                'is_recurring' => $faker->boolean(20),
                'recurrence_pattern' => $faker->boolean(20) ? $faker->randomElement(['daily', 'weekly', 'monthly', 'yearly']) : null,
                'target_audience' => $faker->randomElement(['all', 'students', 'teachers', 'parents', 'staff']),
                'color' => '#'.str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
    
    protected function mapEventType($type)
    {
        $mapping = [
            'exam' => 'exam',
            'holiday' => 'holiday',
            'meeting' => 'meeting',
            'assignment_due' => 'academic',
            'test' => 'exam',
            'event' => 'cultural'
        ];
        
        return $mapping[$type] ?? 'other';
    }
    
    protected function generateEventTitle($type, $faker, $classId, $subjectId, $classes, $subjects)
    {
        $title = '';
        
        // Add subject if available
        if ($subjectId) {
            $subject = $subjects->firstWhere('id', $subjectId);
            if ($subject) {
                $title .= $subject->name . ' - ';
            }
        }
        
        switch ($type) {
            case 'exam':
                $title .= $faker->randomElement(['Examen de ', 'Partiel de ', 'Contrôle de ']) . 
                         $faker->randomElement(['mi-session', 'fin de session', 'rattrapage']);
                break;
                
            case 'holiday':
                $title .= $faker->randomElement([
                    'Vacances de ' . $faker->monthName,
                    'Congé de ' . $faker->monthName,
                    'Fête ' . $faker->randomElement(['nationale', 'religieuse', 'scolaire'])
                ]);
                break;
                
            case 'meeting':
                $title .= $faker->randomElement([
                    'Réunion des professeurs',
                    'Conseil de classe',
                    'Réunion parents-professeurs',
                    'Assemblée générale'
                ]);
                break;
                
            case 'assignment_due':
                $title .= 'Date limite: ' . $faker->words(3, true);
                break;
                
            default:
                $title .= $faker->sentence(3);
        }
        
        return $title;
    }
}
