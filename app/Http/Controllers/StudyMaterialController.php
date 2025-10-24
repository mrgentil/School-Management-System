<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Http\Requests\StudyMaterialRequest;
use App\Models\StudyMaterial;
use App\Models\MyClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StudyMaterialController extends Controller
{
    protected $studyMaterial;

    public function __construct(StudyMaterial $studyMaterial)
    {
        $this->middleware('teamSA', ['except' => ['index', 'show', 'download']]);
        $this->middleware('super_admin', ['only' => ['destroy']]);

        $this->studyMaterial = $studyMaterial;
    }

    public function index()
    {
        $query = $this->studyMaterial->with(['subject', 'myClass', 'uploader']);
        
        // Filtres
        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }
        
        if (request('class_id')) {
            $query->where('my_class_id', request('class_id'));
        }
        
        if (request('subject_id')) {
            $query->where('subject_id', request('subject_id'));
        }
        
        $d['materials'] = $query->latest()->paginate(12);
        $d['classes'] = MyClass::all();
        $d['subjects'] = Subject::all();
        return view('pages.support_team.study_materials.index', $d);
    }

    public function create()
    {
        $d['classes'] = MyClass::all();
        $d['subjects'] = Subject::all();
        return view('pages.support_team.study_materials.create', $d);
    }

    public function store(StudyMaterialRequest $req)
    {
        $data = $req->validated();
        
        if ($req->hasFile('file')) {
            $file = $req->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('study_materials', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getClientOriginalExtension();
        }
        
        $data['uploaded_by'] = Auth::id();
        
        $this->studyMaterial->create($data);
        return Qs::jsonStoreOk();
    }

    public function show($id)
    {
        $d['study_material'] = $studyMaterial = $this->studyMaterial->with(['subject', 'myClass', 'uploader'])->find($id);
        return !is_null($studyMaterial) ? view('pages.support_team.study_materials.show', $d) 
            : Qs::goWithDanger('study-materials.index', __('msg.invalid_id'));
    }

    public function edit($id)
    {
        $d['study_material'] = $studyMaterial = $this->studyMaterial->find($id);
        $d['classes'] = MyClass::all();
        $d['subjects'] = Subject::all();
        return !is_null($studyMaterial) ? view('pages.support_team.study_materials.edit', $d) 
            : Qs::goWithDanger('study-materials.index', __('msg.invalid_id'));
    }

    public function update(StudyMaterialRequest $req, $id)
    {
        $studyMaterial = $this->studyMaterial->find($id);
        if(!$studyMaterial){
            return Qs::goWithDanger('study-materials.index', __('msg.invalid_id'));
        }

        $data = $req->validated();
        
        // Si un nouveau fichier est téléchargé
        if ($req->hasFile('file')) {
            // Supprimer l'ancien fichier
            if ($studyMaterial->file_path && Storage::disk('public')->exists($studyMaterial->file_path)) {
                Storage::disk('public')->delete($studyMaterial->file_path);
            }
            
            $file = $req->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('study_materials', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getClientOriginalExtension();
        }

        $studyMaterial->update($data);
        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $studyMaterial = $this->studyMaterial->find($id);
        if(!$studyMaterial){
            return back()->with('flash_danger', __('msg.invalid_id'));
        }

        // Supprimer le fichier du stockage
        if ($studyMaterial->file_path && Storage::disk('public')->exists($studyMaterial->file_path)) {
            Storage::disk('public')->delete($studyMaterial->file_path);
        }

        $studyMaterial->delete();
        return back()->with('flash_success', __('msg.del_ok'));
    }

    public function download($id)
    {
        $studyMaterial = $this->studyMaterial->find($id);
        
        if (!$studyMaterial) {
            return back()->with('flash_danger', 'Fichier introuvable');
        }

        if (!Storage::disk('public')->exists($studyMaterial->file_path)) {
            return back()->with('flash_danger', 'Le fichier n\'existe plus sur le serveur');
        }

        // Incrémenter le compteur de téléchargements
        $studyMaterial->incrementDownloadCount();

        return Storage::disk('public')->download($studyMaterial->file_path, $studyMaterial->file_name);
    }
}
