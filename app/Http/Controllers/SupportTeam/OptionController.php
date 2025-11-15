<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\AcademicSection;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        $options = Option::with('academic_section')->orderBy('name')->get();
        $sections = AcademicSection::orderBy('name')->get();
        return view('pages.support_team.options.index', compact('options', 'sections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_section_id' => 'required|exists:academic_sections,id',
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:50',
            'active' => 'sometimes|boolean',
        ]);

        $data['active'] = $request->has('active');

        Option::create($data);

        return back()->with('flash_success', 'Option créée avec succès');
    }

    public function update(Request $request, Option $option)
    {
        $data = $request->validate([
            'academic_section_id' => 'required|exists:academic_sections,id',
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:50',
            'active' => 'sometimes|boolean',
        ]);

        $data['active'] = $request->has('active');

        $option->update($data);

        return back()->with('flash_success', 'Option mise à jour');
    }

    public function destroy(Option $option)
    {
        $option->delete();
        return back()->with('flash_success', 'Option supprimée');
    }
}
