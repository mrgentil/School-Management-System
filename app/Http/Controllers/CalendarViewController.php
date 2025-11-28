<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use Illuminate\Http\Request;

class CalendarViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vue calendrier pour tous les utilisateurs
     */
    public function index()
    {
        $user = auth()->user();
        $audience = $user->user_type;

        // Prochains événements filtrés par audience
        $upcomingEvents = SchoolEvent::upcoming()
            ->forAudience($audience)
            ->limit(10)
            ->get();

        return view('pages.calendar.view', compact('upcomingEvents', 'audience'));
    }

    /**
     * API: Événements pour FullCalendar (filtrés par audience)
     */
    public function getEvents(Request $request)
    {
        $user = auth()->user();
        $audience = $user->user_type;

        $events = SchoolEvent::forAudience($audience)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => ($event->start_date ?? $event->event_date)?->format('Y-m-d'),
                    'end' => $event->end_date?->format('Y-m-d'),
                    'color' => $event->color ?? SchoolEvent::getTypeColor($event->type ?? $event->event_type),
                    'allDay' => true,
                    'extendedProps' => [
                        'type' => $event->type ?? $event->event_type,
                        'description' => $event->description,
                    ],
                ];
            });

        return response()->json($events);
    }

    /**
     * Détails d'un événement
     */
    public function show(SchoolEvent $event)
    {
        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'start_date' => $event->formatted_date,
            'end_date' => $event->end_date?->format('d/m/Y'),
            'type_badge' => $event->type_badge,
        ]);
    }
}
