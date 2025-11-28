<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\SchoolEvent;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SchoolCalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    /**
     * Affichage du calendrier
     */
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $currentDate = Carbon::createFromDate($year, $month, 1);
        
        // Récupérer les événements du mois
        $events = SchoolEvent::inMonth($year, $month)->get();

        // Prochains événements
        $upcomingEvents = SchoolEvent::upcoming()->limit(5)->get();

        return view('pages.support_team.calendar.index', compact(
            'events', 'upcomingEvents', 'currentDate', 'year', 'month'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('pages.support_team.calendar.create');
    }

    /**
     * Enregistrer un événement
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'description' => 'nullable|string',
            'target_audience' => 'required|string',
        ]);

        $event = SchoolEvent::create([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'event_type' => $request->type,
            'color' => SchoolEvent::getTypeColor($request->type),
            'target_audience' => $request->target_audience,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('calendar.index')
            ->with('flash_success', 'Événement "' . $event->title . '" créé !');
    }

    /**
     * Modifier un événement
     */
    public function edit(SchoolEvent $event)
    {
        return view('pages.support_team.calendar.edit', compact('event'));
    }

    /**
     * Mettre à jour un événement
     */
    public function update(Request $request, SchoolEvent $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'event_type' => $request->type,
            'color' => SchoolEvent::getTypeColor($request->type),
            'target_audience' => $request->target_audience,
        ]);

        return redirect()->route('calendar.index')
            ->with('flash_success', 'Événement mis à jour !');
    }

    /**
     * Supprimer un événement
     */
    public function destroy(SchoolEvent $event)
    {
        $event->delete();
        return redirect()->route('calendar.index')
            ->with('flash_success', 'Événement supprimé.');
    }

    /**
     * API: Événements pour FullCalendar
     */
    public function getEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $events = SchoolEvent::query()
            ->when($start, fn($q) => $q->where('event_date', '>=', $start))
            ->when($end, fn($q) => $q->where('event_date', '<=', $end))
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->event_date?->format('Y-m-d'),
                    'color' => $event->color ?? SchoolEvent::getTypeColor($event->event_type),
                    'allDay' => true,
                    'extendedProps' => [
                        'type' => $event->event_type,
                        'description' => $event->description,
                    ],
                ];
            });

        return response()->json($events);
    }

    /**
     * Détails d'un événement (modal)
     */
    public function show(SchoolEvent $event)
    {
        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'start_date' => $event->formatted_date,
            'type' => $event->event_type,
            'type_badge' => $event->type_badge,
            'target_audience' => $event->target_audience,
        ]);
    }
}
