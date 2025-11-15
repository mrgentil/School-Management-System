<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\AcademicSection;
use Illuminate\Http\Request;

class AcademicSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        $sections = AcademicSection::orderBy('name')->get();
        return view('pages.support_team.academic_sections.index', compact('sections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:academic_sections,name',
            'code' => 'nullable|string|max:50',
            'active' => 'sometimes|boolean',
        ]);

        $data['active'] = $request->has('active');

        AcademicSection::create($data);

        return back()->with('flash_success', 'Section académique créée avec succès');
    }

    public function update(Request $request, AcademicSection $academic_section)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:academic_sections,name,' . $academic_section->id,
            'code' => 'nullable|string|max:50',
            'active' => 'sometimes|boolean',
        ]);

        $data['active'] = $request->has('active');

        $academic_section->update($data);

        return back()->with('flash_success', 'Section académique mise à jour');
    }

    public function destroy(AcademicSection $academic_section)
    {
        $academic_section->delete();
        return back()->with('flash_success', 'Section académique supprimée');
    }
}
