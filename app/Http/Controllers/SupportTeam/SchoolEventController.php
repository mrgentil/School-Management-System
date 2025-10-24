<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolEvent\SchoolEventCreate;
use App\Http\Requests\SchoolEvent\SchoolEventUpdate;
use App\Models\SchoolEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA', ['except' => ['index', 'show', 'calendar']]);
    }

    public function index()
    {
        $data['events'] = SchoolEvent::with('creator')
            ->forAudience($this->getUserAudience())
            ->upcoming()
            ->paginate(15);

        return view('pages.support_team.events.index', $data);
    }

    public function calendar()
    {
        $events = SchoolEvent::forAudience($this->getUserAudience())
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->event_date->format('Y-m-d') . 
                              ($event->start_time ? 'T' . $event->start_time->format('H:i:s') : ''),
                    'end' => $event->event_date->format('Y-m-d') . 
                            ($event->end_time ? 'T' . $event->end_time->format('H:i:s') : ''),
                    'color' => $event->color,
                    'description' => $event->description,
                    'location' => $event->location,
                ];
            });

        return response()->json($events);
    }

    public function create()
    {
        return view('pages.support_team.events.create');
    }

    public function store(SchoolEventCreate $req)
    {
        $data = $req->validated();
        $data['created_by'] = Auth::id();

        SchoolEvent::create($data);

        return redirect()->route('events.index')->with('flash_success', __('msg.store_ok'));
    }

    public function show(SchoolEvent $event)
    {
        return view('pages.support_team.events.show', compact('event'));
    }

    public function edit(SchoolEvent $event)
    {
        return view('pages.support_team.events.edit', compact('event'));
    }

    public function update(SchoolEventUpdate $req, SchoolEvent $event)
    {
        $event->update($req->validated());

        return redirect()->route('events.index')->with('flash_success', __('msg.update_ok'));
    }

    public function destroy(SchoolEvent $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('flash_success', __('msg.del_ok'));
    }

    private function getUserAudience()
    {
        $userType = Auth::user()->user_type;
        
        switch ($userType) {
            case 'student':
                return 'students';
            case 'teacher':
                return 'teachers';
            case 'parent':
                return 'parents';
            default:
                return 'staff';
        }
    }
}
