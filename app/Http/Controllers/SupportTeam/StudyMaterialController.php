<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudyMaterial\StudyMaterialCreate;
use App\Http\Requests\StudyMaterial\StudyMaterialUpdate;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\MyClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudyMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSAT', ['except' => ['index', 'show', 'download']]);
    }

    public function index(Request $request)
    {
        $query = StudyMaterial::with(['subject', 'myClass', 'uploader']);

        // Filtres
        if ($request->filled('class_id')) {
            $query->forClass($request->class_id);
        }

        if ($request->filled('subject_id')) {
            $query->forSubject($request->subject_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Si l'utilisateur n'est pas admin, ne montrer que les matériaux publics
        if (!Qs::userIsTeamSAT()) {
            $query->public();
        }

        $data['materials'] = $query->latest()->paginate(15);
        $data['classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();

        return view('pages.support_team.study_materials.index', $data);
    }

    public function create()
    {
        $data['classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        
        return view('pages.support_team.study_materials.create', $data);
    }

    public function store(StudyMaterialCreate $req)
    {
        $data = $req->validated();
        
        if ($req->hasFile('file')) {
            $file = $req->file('file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('study_materials', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getClientMimeType();
        }
        
        $data['uploaded_by'] = Auth::id();
        
        StudyMaterial::create($data);

        return redirect()->route('study-materials.index')->with('flash_success', __('msg.store_ok'));
    }

    public function show(StudyMaterial $studyMaterial)
    {
        return view('pages.support_team.study_materials.show', compact('studyMaterial'));
    }

    public function edit(StudyMaterial $studyMaterial)
    {
        $data['material'] = $studyMaterial;
        $data['classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        
        return view('pages.support_team.study_materials.edit', $data);
    }

    public function update(StudyMaterialUpdate $req, StudyMaterial $studyMaterial)
    {
        $data = $req->validated();
        
        if ($req->hasFile('file')) {
            // Supprimer l'ancien fichier
            if ($studyMaterial->file_path && Storage::disk('public')->exists($studyMaterial->file_path)) {
                Storage::disk('public')->delete($studyMaterial->file_path);
            }
            
            $file = $req->file('file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('study_materials', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getClientMimeType();
        }
        
        $studyMaterial->update($data);

        return redirect()->route('study-materials.index')->with('flash_success', __('msg.update_ok'));
    }

    public function destroy(StudyMaterial $studyMaterial)
    {
        // Supprimer le fichier
        if ($studyMaterial->file_path && Storage::disk('public')->exists($studyMaterial->file_path)) {
            Storage::disk('public')->delete($studyMaterial->file_path);
        }
        
        $studyMaterial->delete();

        return redirect()->route('study-materials.index')->with('flash_success', __('msg.del_ok'));
    }

    public function download(StudyMaterial $studyMaterial)
    {
        if (!Storage::disk('public')->exists($studyMaterial->file_path)) {
            return redirect()->back()->with('flash_danger', 'Fichier non trouvé.');
        }

        // Incrémenter le compteur de téléchargements
        $studyMaterial->incrementDownloadCount();

        return Storage::disk('public')->download($studyMaterial->file_path, $studyMaterial->file_name);
    }
}
