<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\ExamSchedule;
use App\Models\ExamRoom;
use App\Services\ExamPlacementService;
use Illuminate\Http\Request;

class ExamPlacementController extends Controller
{
    protected $placementService;

    public function __construct(ExamPlacementService $placementService)
    {
        $this->middleware('teamSA');
        $this->placementService = $placementService;
    }

    /**
     * Générer les placements automatiques pour un examen SESSION
     */
    public function generate($exam_schedule_id)
    {
        try {
            $schedule = ExamSchedule::with('exam')->findOrFail($exam_schedule_id);

            // Vérifier que c'est bien un examen SESSION
            if ($schedule->exam->exam_type !== 'session') {
                return back()->with('flash_danger', 'Le placement automatique est uniquement pour les examens SESSION');
            }

            // Générer les placements
            $result = $this->placementService->placeStudentsForSession($exam_schedule_id);

            return back()->with('flash_success', 
                "Placement réussi ! {$result['total_students']} étudiants placés dans {$result['rooms_used']} salle(s)."
            );

        } catch (\Exception $e) {
            return back()->with('flash_danger', 'Erreur lors du placement: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les placements d'un examen
     */
    public function show($exam_schedule_id)
    {
        $d['schedule'] = ExamSchedule::with(['exam', 'my_class', 'section', 'subject'])->findOrFail($exam_schedule_id);
        
        // Vérifier que c'est un examen SESSION
        if ($d['schedule']->exam->exam_type !== 'session') {
            return redirect()->route('exam_schedules.show', $d['schedule']->exam_id)
                ->with('flash_info', 'Cet examen HORS SESSION ne nécessite pas de placement');
        }

        // Récupérer les placements groupés par salle
        $d['placementsByRoom'] = $this->placementService->getPlacementsByRoom($exam_schedule_id);
        $d['rooms'] = ExamRoom::active()->get();

        return view('pages.support_team.exam_placements.show', $d);
    }

    /**
     * Afficher les placements par salle (pour impression)
     */
    public function byRoom($exam_schedule_id, $room_id)
    {
        $d['schedule'] = ExamSchedule::with(['exam', 'my_class', 'section', 'subject'])->findOrFail($exam_schedule_id);
        $d['room'] = ExamRoom::findOrFail($room_id);
        
        $allPlacements = $this->placementService->getPlacementsByRoom($exam_schedule_id);
        $d['placements'] = $allPlacements->get($room_id, collect());

        return view('pages.support_team.exam_placements.by_room', $d);
    }

    /**
     * Supprimer tous les placements d'un examen
     */
    public function destroy($exam_schedule_id)
    {
        $schedule = ExamSchedule::findOrFail($exam_schedule_id);
        
        $count = $schedule->placements()->count();
        $schedule->placements()->delete();

        return back()->with('flash_success', "$count placement(s) supprimé(s)");
    }
}
