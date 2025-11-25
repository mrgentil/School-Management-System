<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Http\Requests\Student\StudentRecordCreate;
use App\Http\Requests\Student\StudentRecordUpdate;
use App\Models\AcademicSection;
use App\Models\Option;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentRecordController extends Controller
{
    protected $loc, $my_class, $user, $student;

   public function __construct(LocationRepo $loc, MyClassRepo $my_class, UserRepo $user, StudentRepo $student)
   {
       $this->middleware('teamSA', ['only' => ['edit','update', 'reset_pass', 'create', 'store', 'graduated'] ]);
       $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->loc = $loc;
        $this->my_class = $my_class;
        $this->user = $user;
        $this->student = $student;
   }

    public function reset_pass($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        $data['password'] = Hash::make('password');
        $this->user->update($st_id, $data);
        return back()->with('flash_success', __('msg.p_reset'));
    }

    public function create()
    {
        // Charger les classes avec leurs relations complètes pour afficher les noms complets
        $data['my_classes'] = \App\Models\MyClass::with(['academicSection', 'option'])
            ->orderBy('name')
            ->get();
        $data['parents'] = $this->user->getUserByType('parent');
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        $data['academic_sections'] = AcademicSection::all();
        $data['options'] = Option::with('academic_section')->get();
        return view('pages.support_team.students.add', $data);
    }

    public function store(StudentRecordCreate $req)
    {
       $data =  $req->only(Qs::getUserRecord());
       $sr =  $req->only(Qs::getStudentData());

        $ct = $this->my_class->findTypeByClass($req->my_class_id)->code;
       /* $ct = ($ct == 'J') ? 'JSS' : $ct;
        $ct = ($ct == 'S') ? 'SS' : $ct;*/

        $data['user_type'] = 'student';
        $data['name'] = ucwords($req->name);
        $data['code'] = strtoupper(Str::random(10));
        $data['password'] = Hash::make('student');
        $data['photo'] = Qs::getDefaultUserImage();
        $adm_no = $req->adm_no;
        $data['username'] = strtoupper(Qs::getAppCode().'/'.$ct.'/'.$sr['year_admitted'].'/'.($adm_no ?: mt_rand(1000, 99999)));

        if($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath('student').$data['code'], $f['name'], 'public');
            $data['photo'] = asset('storage/' . $f['path']);
        }

        $user = $this->user->create($data); // Create User

        $sr['adm_no'] = $data['username'];
        $sr['user_id'] = $user->id;
        $sr['session'] = Qs::getSetting('current_session');

        $this->student->createRecord($sr); // Create Student
        return Qs::jsonStoreOk();
    }

    public function listByClass($class_id)
    {
        $data['my_class'] = $mc = $this->my_class->getMC(['id' => $class_id])->first();
        $data['students'] = $this->student->findStudentsByClass($class_id);
        $data['sections'] = $this->my_class->getClassSections($class_id);

        return is_null($mc) ? Qs::goWithDanger() : view('pages.support_team.students.list', $data);
    }

    public function graduated()
    {
        $data['my_classes'] = $this->my_class->all();
        $data['students'] = $this->student->allGradStudents();

        return view('pages.support_team.students.graduated', $data);
    }

    public function not_graduated($sr_id)
    {
        $d['grad'] = 0;
        $d['grad_date'] = NULL;
        $d['session'] = Qs::getSetting('current_session');
        $this->student->updateRecord($sr_id, $d);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function show($sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if(!$sr_id){return Qs::goWithDanger();}

        $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();

        /* Prevent Other Students/Parents from viewing Profile of others */
        if(Auth::user()->id != $data['sr']->user_id && !Qs::userIsTeamSAT() && !Qs::userIsMyChild($data['sr']->user_id, Auth::user()->id)){
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        return view('pages.support_team.students.show', $data);
    }

    public function edit($sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if(!$sr_id){return Qs::goWithDanger();}

        $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();
        $data['my_classes'] = $this->my_class->all();
        $data['parents'] = $this->user->getUserByType('parent');
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        $data['academic_sections'] = AcademicSection::all();
        $data['options'] = Option::with('academic_section')->get();
        return view('pages.support_team.students.edit', $data);
    }

    public function update(StudentRecordUpdate $req, $sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if(!$sr_id){return Qs::goWithDanger();}

        $sr = $this->student->getRecord(['id' => $sr_id])->first();
        $d =  $req->only(Qs::getUserRecord());
        $d['name'] = ucwords($req->name);

        if($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath('student').$sr->user->code, $f['name'], 'public');
            $d['photo'] = asset('storage/' . $f['path']);
        }

        $this->user->update($sr->user->id, $d); // Update User Details

        $srec = $req->only(Qs::getStudentData());

        $this->student->updateRecord($sr_id, $srec); // Update St Rec

        /*** If Class/Section is Changed in Same Year, Delete Marks/ExamRecord of Previous Class/Section ****/
        Mk::deleteOldRecord($sr->user->id, $srec['my_class_id']);

        return Qs::jsonUpdateOk();
    }

    public function destroy($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        if(!$st_id){return Qs::goWithDanger();}

        $sr = $this->student->getRecord(['user_id' => $st_id])->first();
        $path = Qs::getUploadPath('student').$sr->user->code;
        Storage::exists($path) ? Storage::deleteDirectory($path) : false;
        $this->user->delete($sr->user->id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

    public function assign_class()
    {
        // Charger les classes avec leurs relations complètes pour afficher les noms complets
        $data['my_classes'] = \App\Models\MyClass::with(['academicSection', 'option'])
            ->orderBy('name')
            ->get();
        
        // Récupérer tous les étudiants qui n'ont pas d'enregistrement pour l'année courante
        $currentSession = Qs::getCurrentSession();
        
        // Récupérer les IDs des étudiants déjà assignés pour cette session
        $assignedStudentIds = \App\Models\StudentRecord::where('session', $currentSession)
            ->pluck('user_id')
            ->toArray();
        
        // Récupérer tous les étudiants non assignés
        $data['unassigned_students'] = \App\Models\User::where('user_type', 'student')
            ->whereNotIn('id', $assignedStudentIds)
            ->orderBy('name')
            ->get();
            
        // Récupérer tous les étudiants assignés pour l'année courante avec relations complètes
        $data['assigned_students'] = $this->student->getRecord(['session' => $currentSession])
            ->with(['user', 'my_class.academicSection', 'my_class.option'])
            ->get();
            
        return view('pages.support_team.students.assign_class', $data);
    }

    public function store_assignment(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'my_class_id' => 'required|exists:my_classes,id',
        ]);

        $currentSession = Qs::getCurrentSession();
        
        // Vérifier si l'étudiant n'est pas déjà assigné pour cette session
        $existingRecord = $this->student->getRecord([
            'user_id' => $request->student_id,
            'session' => $currentSession
        ])->first();
        
        if ($existingRecord) {
            return back()->with('flash_danger', 'Cet étudiant est déjà assigné à une classe pour cette session.');
        }

        // Récupérer ou créer la première section de cette classe
        $defaultSection = \App\Models\Section::where('my_class_id', $request->my_class_id)->first();
        
        if (!$defaultSection) {
            // Créer automatiquement une section par défaut pour cette classe
            $class = \App\Models\MyClass::find($request->my_class_id);
            $defaultSection = \App\Models\Section::create([
                'name' => 'A', // Section par défaut
                'my_class_id' => $request->my_class_id,
                'active' => 1,
                'teacher_id' => null, // Peut être assigné plus tard
            ]);
            
            \Log::info("Section par défaut créée automatiquement pour la classe {$class->name} (ID: {$class->id})");
        }

        // Créer l'enregistrement étudiant
        $data = [
            'user_id' => $request->student_id,
            'my_class_id' => $request->my_class_id,
            'section_id' => $defaultSection->id,
            'session' => $currentSession,
            'adm_no' => $this->generateAdmissionNumber(),
        ];

        $this->student->createRecord($data);

        return back()->with('flash_success', 'Étudiant assigné à la classe avec succès!');
    }

    public function update_assignment(\Illuminate\Http\Request $request, $sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if(!$sr_id){return Qs::goWithDanger();}

        $request->validate([
            'my_class_id' => 'required|exists:my_classes,id',
        ]);

        $sr = $this->student->getRecord(['id' => $sr_id])->first();
        if (!$sr) {
            return back()->with('flash_danger', 'Enregistrement étudiant non trouvé.');
        }

        // Récupérer ou créer la première section de la nouvelle classe
        $defaultSection = \App\Models\Section::where('my_class_id', $request->my_class_id)->first();
        
        if (!$defaultSection) {
            // Créer automatiquement une section par défaut pour cette classe
            $class = \App\Models\MyClass::find($request->my_class_id);
            $defaultSection = \App\Models\Section::create([
                'name' => 'A', // Section par défaut
                'my_class_id' => $request->my_class_id,
                'active' => 1,
                'teacher_id' => null, // Peut être assigné plus tard
            ]);
            
            \Log::info("Section par défaut créée automatiquement pour la classe {$class->name} (ID: {$class->id})");
        }

        $data = [
            'my_class_id' => $request->my_class_id,
            'section_id' => $defaultSection->id,
        ];

        $this->student->updateRecord($sr_id, $data);

        return back()->with('flash_success', 'Assignation de classe mise à jour avec succès!');
    }

    private function generateAdmissionNumber()
    {
        $year = date('Y');
        $lastRecord = $this->student->getRecord(['session' => Qs::getCurrentSession()])
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = $lastRecord ? (intval(substr($lastRecord->adm_no, -4)) + 1) : 1;
        
        return $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

}
