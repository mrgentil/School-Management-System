<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\MyClass\ClassCreate;
use App\Http\Requests\MyClass\ClassUpdate;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;

class MyClassController extends Controller
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->my_class = $my_class;
        $this->user = $user;
    }

    public function index()
    {
        $d['my_classes'] = $this->my_class->all();
        $d['class_types'] = $this->my_class->getTypes();
        $d['academic_sections'] = \App\Models\AcademicSection::all(); // Gardé pour le groupement des options
        $d['options'] = \App\Models\Option::with('academic_section')->get();

        return view('pages.support_team.classes.index', $d);
    }

    public function store(ClassCreate $req)
    {
        $data = $req->only(['class_type_id', 'academic_level', 'division', 'academic_option', 'option_id']);
        
        // Si une option est sélectionnée, récupérer automatiquement sa section académique
        if ($req->option_id) {
            $option = \App\Models\Option::find($req->option_id);
            if ($option && $option->academic_section_id) {
                $data['academic_section_id'] = $option->academic_section_id;
            }
        }
        
        // Générer le nom automatiquement si pas fourni
        if (!$req->filled('name')) {
            $nameParts = [];
            if ($req->academic_level) {
                $nameParts[] = $req->academic_level;
            }
            if ($req->division) {
                $nameParts[] = $req->division;
            }
            if ($req->academic_option) {
                $nameParts[] = $req->academic_option;
            }
            $data['name'] = implode(' ', $nameParts);
        } else {
            $data['name'] = $req->name;
        }
        
        $mc = $this->my_class->create($data);

        // Create Default Section - Ne pas créer si division est déjà spécifiée
        if (!$req->has('division')) {
            $s =['my_class_id' => $mc->id,
                'name' => 'A',
                'active' => 1,
                'teacher_id' => NULL,
            ];

            $this->my_class->createSection($s);
        }

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['c'] = $c = $this->my_class->find($id);

        return is_null($c) ? Qs::goWithDanger('classes.index') : view('pages.support_team.classes.edit', $d) ;
    }

    public function update(ClassUpdate $req, $id)
    {
        $data = $req->only(['name']);
        $this->my_class->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $this->my_class->delete($id);
        return back()->with('flash_success', __('msg.del_ok'));
    }

}
