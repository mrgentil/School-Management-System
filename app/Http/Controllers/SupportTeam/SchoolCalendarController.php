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
            'start_date' => $request->start_date,
            'event_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'type' => $request->type,
            'event_type' => $request->type,
            'color' => SchoolEvent::getTypeColor($request->type),
            'is_all_day' => !$request->start_time,
            'target_audience' => $request->target_audience,
            'send_notification' => $request->boolean('send_notification'),
            'created_by' => auth()->id(),
            'is_active' => true,
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
            'start_date' => $request->start_date,
            'event_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'type' => $request->type,
            'event_type' => $request->type,
            'color' => SchoolEvent::getTypeColor($request->type),
            'target_audience' => $request->target_audience,
            'send_notification' => $request->boolean('send_notification'),
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
            ->when($start, fn($q) => $q->where('start_date', '>=', $start)->orWhere('event_date', '>=', $start))
            ->when($end, fn($q) => $q->where('start_date', '<=', $end)->orWhere('event_date', '<=', $end))
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => ($event->start_date ?? $event->event_date)?->format('Y-m-d'),
                    'end' => $event->end_date?->format('Y-m-d'),
                    'color' => $event->color ?? SchoolEvent::getTypeColor($event->type ?? $event->event_type),
                    'allDay' => $event->is_all_day ?? true,
                    'extendedProps' => [
                        'type' => $event->type ?? $event->event_type,
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
            'end_date' => $event->end_date?->format('d/m/Y'),
            'type' => $event->type ?? $event->event_type,
            'type_badge' => $event->type_badge,
            'target_audience' => $event->target_audience,
        ]);
    }
}
