<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TimeTableRecord;
use App\Models\TimeTable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche l'emploi du temps de l'étudiant
     */
    public function index()
    {
        $user = auth()->user();
        
        // Récupérer la classe de l'étudiant
        $studentRecord = $user->student_record;
        
        if (!$studentRecord || !$studentRecord->my_class_id) {
            return view('pages.student.timetable.index', [
                'timetable' => null,
                'message' => 'Vous n\'êtes pas encore assigné à une classe.'
            ]);
        }
        
        $classId = $studentRecord->my_class_id;
        $className = $studentRecord->my_class->name ?? 'N/A';
        
        // Récupérer l'emploi du temps de la classe
        $timetableRecord = TimeTableRecord::where('my_class_id', $classId)
            ->with(['my_class', 'exam'])
            ->latest()
            ->first();
        
        if (!$timetableRecord) {
            return view('pages.student.timetable.index', [
                'timetable' => null,
                'className' => $className,
                'message' => 'Aucun emploi du temps n\'a été créé pour votre classe.'
            ]);
        }
        
        // Récupérer tous les cours de l'emploi du temps
        $timetables = TimeTable::where('ttr_id', $timetableRecord->id)
            ->with(['subject', 'time_slot'])
            ->orderBy('day')
            ->orderBy('timestamp_from')
            ->get();
        
        // Organiser les cours par jour
        $schedule = $this->organizeByDay($timetables);
        
        return view('pages.student.timetable.index', [
            'timetableRecord' => $timetableRecord,
            'schedule' => $schedule,
            'className' => $className,
            'timetable' => $timetableRecord
        ]);
    }
    
    /**
     * Affiche l'emploi du temps en vue calendrier
     */
    public function calendar()
    {
        $user = auth()->user();
        $studentRecord = $user->student_record;
        
        if (!$studentRecord || !$studentRecord->my_class_id) {
            return view('pages.student.timetable.calendar', [
                'events' => [],
                'message' => 'Vous n\'êtes pas encore assigné à une classe.'
            ]);
        }
        
        $classId = $studentRecord->my_class_id;
        
        // Récupérer l'emploi du temps de la classe
        $timetableRecord = TimeTableRecord::where('my_class_id', $classId)
            ->latest()
            ->first();
        
        if (!$timetableRecord) {
            return view('pages.student.timetable.calendar', [
                'events' => [],
                'message' => 'Aucun emploi du temps n\'a été créé pour votre classe.'
            ]);
        }
        
        // Récupérer tous les cours
        $timetables = TimeTable::where('ttr_id', $timetableRecord->id)
            ->with(['subject', 'time_slot'])
            ->get();
        
        // Convertir en format événements pour le calendrier
        $events = $this->convertToCalendarEvents($timetables);
        
        return view('pages.student.timetable.calendar', [
            'events' => $events,
            'timetableRecord' => $timetableRecord
        ]);
    }
    
    /**
     * Organise les cours par jour de la semaine
     */
    private function organizeByDay($timetables)
    {
        $days = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche'
        ];
        
        $schedule = [];
        
        foreach ($days as $dayEn => $dayFr) {
            $schedule[$dayFr] = $timetables->filter(function($tt) use ($dayEn) {
                return $tt->day === $dayEn;
            })->sortBy('timestamp_from')->values();
        }
        
        return $schedule;
    }
    
    /**
     * Convertit les cours en événements pour le calendrier
     */
    private function convertToCalendarEvents($timetables)
    {
        $events = [];
        $colors = [
            'Monday' => '#3498db',
            'Tuesday' => '#2ecc71',
            'Wednesday' => '#f39c12',
            'Thursday' => '#9b59b6',
            'Friday' => '#e74c3c',
            'Saturday' => '#1abc9c',
            'Sunday' => '#95a5a6'
        ];
        
        foreach ($timetables as $tt) {
            if ($tt->time_slot) {
                $events[] = [
                    'title' => $tt->subject->name ?? 'Cours',
                    'start' => $tt->time_slot->time_from,
                    'end' => $tt->time_slot->time_to,
                    'color' => $colors[$tt->day] ?? '#3498db',
                    'dow' => [$this->getDayOfWeek($tt->day)],
                    'description' => $tt->subject->name ?? 'N/A'
                ];
            }
        }
        
        return $events;
    }
    
    /**
     * Convertit le nom du jour en numéro (0 = Dimanche, 1 = Lundi, etc.)
     */
    private function getDayOfWeek($day)
    {
        $days = [
            'Sunday' => 0,
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6
        ];
        
        return $days[$day] ?? 1;
    }
}
