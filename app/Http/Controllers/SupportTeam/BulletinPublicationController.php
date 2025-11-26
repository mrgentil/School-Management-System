<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\BulletinPublication;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\UserNotification;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BulletinPublicationController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->middleware('teamSA');
        $this->year = Qs::getCurrentSession();
    }

    /**
     * Dashboard de gestion des publications
     */
    public function index()
    {
        $d['classes'] = MyClass::with('section')->orderBy('name')->get();
        $d['sections'] = Section::all();
        $d['year'] = $this->year;
        $d['periods'] = [1 => 'Période 1', 2 => 'Période 2', 3 => 'Période 3', 4 => 'Période 4'];
        $d['semesters'] = [1 => 'Semestre 1', 2 => 'Semestre 2'];

        // Récupérer toutes les publications de l'année
        $d['publications'] = BulletinPublication::where('year', $this->year)
            ->with(['myClass', 'section', 'publisher'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Créer une matrice de statuts par classe/période
        $d['statusMatrix'] = $this->buildStatusMatrix();

        return view('pages.support_team.bulletin_publications.index', $d);
    }

    /**
     * Construire la matrice de statuts
     */
    protected function buildStatusMatrix()
    {
        $classes = MyClass::orderBy('name')->get();
        $matrix = [];

        foreach ($classes as $class) {
            $matrix[$class->id] = [
                'class' => $class,
                'periods' => [],
                'semesters' => [],
            ];

            // Périodes 1-4
            for ($p = 1; $p <= 4; $p++) {
                $status = BulletinPublication::getPublicationStatus(
                    $class->id, 
                    BulletinPublication::TYPE_PERIOD, 
                    $p, 
                    $this->year
                );
                $matrix[$class->id]['periods'][$p] = $status;
            }

            // Semestres 1-2
            for ($s = 1; $s <= 2; $s++) {
                $status = BulletinPublication::getPublicationStatus(
                    $class->id, 
                    BulletinPublication::TYPE_SEMESTER, 
                    $s, 
                    $this->year
                );
                $matrix[$class->id]['semesters'][$s] = $status;
            }
        }

        return $matrix;
    }

    /**
     * Publier un bulletin (période ou semestre)
     */
    public function publish(Request $request)
    {
        $request->validate([
            'type' => 'required|in:period,semester',
            'period' => 'required_if:type,period|nullable|integer|min:1|max:4',
            'semester' => 'required_if:type,semester|nullable|integer|min:1|max:2',
            'my_class_id' => 'nullable|exists:my_classes,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $type = $request->type;
        $classId = $request->my_class_id;
        $periodOrSemester = $type === 'period' ? $request->period : $request->semester;

        // Vérifier si déjà publié
        $existing = BulletinPublication::where('year', $this->year)
            ->where('type', $type)
            ->where($type === 'period' ? 'period' : 'semester', $periodOrSemester)
            ->where(function($q) use ($classId) {
                if ($classId) {
                    $q->where('my_class_id', $classId);
                } else {
                    $q->whereNull('my_class_id');
                }
            })
            ->first();

        if ($existing) {
            // Mettre à jour
            $existing->status = BulletinPublication::STATUS_PUBLISHED;
            $existing->published_at = now();
            $existing->published_by = Auth::id();
            $existing->notes = $request->notes;
            $existing->save();
            $publication = $existing;
        } else {
            // Créer
            $publication = BulletinPublication::create([
                'my_class_id' => $classId,
                'type' => $type,
                'period' => $type === 'period' ? $request->period : null,
                'semester' => $type === 'semester' ? $request->semester : null,
                'year' => $this->year,
                'status' => BulletinPublication::STATUS_PUBLISHED,
                'published_at' => now(),
                'published_by' => Auth::id(),
                'notes' => $request->notes,
            ]);
        }

        // Envoyer les notifications
        $this->sendNotifications($publication);

        $label = $type === 'period' ? "Période {$request->period}" : "Semestre {$request->semester}";
        $classLabel = $classId ? MyClass::find($classId)->name : 'Toutes les classes';

        return redirect()->back()->with('flash_success', "Bulletin {$label} publié pour {$classLabel}. Les étudiants ont été notifiés.");
    }

    /**
     * Dépublier un bulletin
     */
    public function unpublish(Request $request)
    {
        $request->validate([
            'type' => 'required|in:period,semester',
            'period' => 'required_if:type,period|nullable|integer|min:1|max:4',
            'semester' => 'required_if:type,semester|nullable|integer|min:1|max:2',
            'my_class_id' => 'nullable|exists:my_classes,id',
        ]);

        $type = $request->type;
        $classId = $request->my_class_id;
        $periodOrSemester = $type === 'period' ? $request->period : $request->semester;

        $publication = BulletinPublication::where('year', $this->year)
            ->where('type', $type)
            ->where($type === 'period' ? 'period' : 'semester', $periodOrSemester)
            ->where(function($q) use ($classId) {
                if ($classId) {
                    $q->where('my_class_id', $classId);
                } else {
                    $q->whereNull('my_class_id');
                }
            })
            ->first();

        if ($publication) {
            $publication->status = BulletinPublication::STATUS_DRAFT;
            $publication->save();
        }

        return redirect()->back()->with('flash_success', 'Bulletin dépublié avec succès.');
    }

    /**
     * Publier en masse (toutes les classes pour une période)
     */
    public function publishAll(Request $request)
    {
        $request->validate([
            'type' => 'required|in:period,semester',
            'period' => 'required_if:type,period|nullable|integer|min:1|max:4',
            'semester' => 'required_if:type,semester|nullable|integer|min:1|max:2',
        ]);

        $type = $request->type;
        $periodOrSemester = $type === 'period' ? $request->period : $request->semester;

        // Créer ou mettre à jour une publication globale (my_class_id = null)
        $publication = BulletinPublication::updateOrCreate(
            [
                'year' => $this->year,
                'type' => $type,
                $type === 'period' ? 'period' : 'semester' => $periodOrSemester,
                'my_class_id' => null,
            ],
            [
                'status' => BulletinPublication::STATUS_PUBLISHED,
                'published_at' => now(),
                'published_by' => Auth::id(),
            ]
        );

        // Envoyer les notifications à tous les étudiants
        $this->sendNotifications($publication);

        $label = $type === 'period' ? "Période {$request->period}" : "Semestre {$request->semester}";

        return redirect()->back()->with('flash_success', "Bulletin {$label} publié pour TOUTES les classes. Tous les étudiants ont été notifiés.");
    }

    /**
     * Envoyer les notifications aux étudiants
     */
    protected function sendNotifications(BulletinPublication $publication)
    {
        $query = StudentRecord::query();

        if ($publication->my_class_id) {
            $query->where('my_class_id', $publication->my_class_id);
        }

        $students = $query->get();

        $typeLabel = $publication->type === BulletinPublication::TYPE_PERIOD 
            ? "Période {$publication->period}" 
            : "Semestre {$publication->semester}";

        $url = route('student.grades.bulletin', [
            'type' => $publication->type,
            'period' => $publication->period ?? 1,
            'semester' => $publication->semester ?? 1,
        ]);

        foreach ($students as $student) {
            UserNotification::create([
                'user_id' => $student->user_id,
                'type' => UserNotification::TYPE_BULLETIN_PUBLISHED,
                'title' => '📋 Bulletin disponible',
                'message' => "Votre bulletin de notes ({$typeLabel}) est maintenant disponible. Cliquez pour le consulter.",
                'data' => [
                    'type' => $publication->type,
                    'period' => $publication->period,
                    'semester' => $publication->semester,
                    'year' => $publication->year,
                    'url' => $url,
                ],
            ]);
        }
    }

    /**
     * Historique des publications
     */
    public function history()
    {
        $d['publications'] = BulletinPublication::with(['myClass', 'section', 'publisher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pages.support_team.bulletin_publications.history', $d);
    }
}
