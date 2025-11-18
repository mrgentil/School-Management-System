<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\ExamRoom;
use Illuminate\Http\Request;

class ExamRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    /**
     * Liste des salles d'examen
     */
    public function index()
    {
        $d['rooms'] = ExamRoom::orderBy('level')->orderBy('code')->get();
        return view('pages.support_team.exam_rooms.index', $d);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('pages.support_team.exam_rooms.create');
    }

    /**
     * Enregistrer une nouvelle salle
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:exam_rooms,code',
            'building' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:10|max:100',
            'level' => 'required|in:excellence,moyen,faible',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        ExamRoom::create($data);

        return redirect()->route('exam_rooms.index')->with('flash_success', 'Salle créée avec succès');
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $d['room'] = ExamRoom::findOrFail($id);
        return view('pages.support_team.exam_rooms.edit', $d);
    }

    /**
     * Mettre à jour une salle
     */
    public function update(Request $request, $id)
    {
        $room = ExamRoom::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:exam_rooms,code,' . $id,
            'building' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:10|max:100',
            'level' => 'required|in:excellence,moyen,faible',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $room->update($data);

        return redirect()->route('exam_rooms.index')->with('flash_success', 'Salle mise à jour avec succès');
    }

    /**
     * Supprimer une salle
     */
    public function destroy($id)
    {
        $room = ExamRoom::findOrFail($id);
        
        // Vérifier si la salle est utilisée
        if ($room->placements()->count() > 0) {
            return back()->with('flash_danger', 'Impossible de supprimer cette salle car elle est utilisée dans des placements d\'étudiants');
        }

        $room->delete();

        return back()->with('flash_success', 'Salle supprimée avec succès');
    }
}
