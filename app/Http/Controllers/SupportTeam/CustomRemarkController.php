<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\CustomRemark;
use Illuminate\Http\Request;
use App\Helpers\Qs;

class CustomRemarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    /**
     * Créer une nouvelle mention personnalisée
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:custom_remarks,name',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
            'active' => 'boolean'
        ]);

        CustomRemark::create([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order,
            'active' => $request->has('active')
        ]);

        return back()->with('flash_success', 'Mention personnalisée ajoutée avec succès !');
    }

    /**
     * Mettre à jour une mention personnalisée
     */
    public function update(Request $request, CustomRemark $customRemark)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:custom_remarks,name,' . $customRemark->id,
            'description' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
            'active' => 'boolean'
        ]);

        $customRemark->update([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order,
            'active' => $request->has('active')
        ]);

        return back()->with('flash_success', 'Mention personnalisée mise à jour avec succès !');
    }

    /**
     * Supprimer une mention personnalisée
     */
    public function destroy(CustomRemark $customRemark)
    {
        if (!Qs::userIsSuperAdmin()) {
            return back()->with('flash_danger', 'Action non autorisée.');
        }

        $customRemark->delete();

        return back()->with('flash_success', 'Mention personnalisée supprimée avec succès !');
    }
}
