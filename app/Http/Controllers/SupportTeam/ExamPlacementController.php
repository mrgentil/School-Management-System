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
     * Générer les placements automatiques pour un examen SESSION complet
     * LOGIQUE CORRECTE: Un élève a UNE salle et UN numéro de place pour TOUT l'examen
     */
    public function generate($exam_id)
    {
        try {
            $exam = Exam::with('schedules')->findOrFail($exam_id);

            // Vérifier qu'il y a au moins un horaire SESSION
            $hasSessionSchedules = $exam->schedules->where('exam_type', 'session')->count() > 0;
            if (!$hasSessionSchedules) {
                return back()->with('flash_danger', 'Cet examen n\'a aucun horaire de type SESSION');
            }

            // Générer les placements pour tout l'examen
            $result = $this->placementService->placeStudentsForSession($exam_id);

            return back()->with('flash_success', 
                "Placement réussi ! {$result['total_students']} étudiants placés dans {$result['rooms_used']} salle(s) pour TOUT l'examen."
            );

        } catch (\Exception $e) {
            return back()->with('flash_danger', 'Erreur lors du placement: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les placements d'un examen SESSION complet
     */
    public function show($exam_id)
    {
        $d['exam'] = Exam::with('schedules')->findOrFail($exam_id);
        
        // Vérifier qu'il y a au moins un horaire SESSION
        $hasSessionSchedules = $d['exam']->schedules->where('exam_type', 'session')->count() > 0;
        if (!$hasSessionSchedules) {
            return redirect()->route('exams.index')
                ->with('flash_info', 'Cet examen n\'a pas d\'horaires SESSION');
        }

        // Récupérer les placements groupés par salle
        $d['placementsByRoom'] = $this->placementService->getPlacementsByRoom($exam_id);
        $d['rooms'] = ExamRoom::active()->get();

        return view('pages.support_team.exam_placements.show', $d);
    }

    /**
     * Afficher les placements par salle (pour impression)
     */
    public function byRoom($exam_id, $room_id)
    {
        $d['exam'] = Exam::with('schedules')->findOrFail($exam_id);
        $d['room'] = ExamRoom::findOrFail($room_id);
        
        $allPlacements = $this->placementService->getPlacementsByRoom($exam_id);
        $d['placements'] = $allPlacements->get($room_id, collect());

        return view('pages.support_team.exam_placements.by_room', $d);
    }

    /**
     * Supprimer tous les placements d'un examen SESSION complet
     */
    public function destroy($exam_id)
    {
        $exam = Exam::findOrFail($exam_id);
        
        $count = $exam->placements()->count();
        $exam->placements()->delete();

        return back()->with('flash_success', "$count placement(s) supprimé(s) pour tout l'examen");
    }
}
