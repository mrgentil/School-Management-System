<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Models\Pin;
use App\Models\MyClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PinController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->middleware('teamSA', ['except' => ['verify', 'enter_pin', 'verifyForBulletin']]);
        $this->year = Qs::getCurrentSession();
    }

    /**
     * Liste des PINs
     */
    public function index(Request $request)
    {
        $type = $request->type ?? 'bulletin';
        
        $d['pin_count'] = Pin::valid()->ofType($type)->count();
        $d['valid_pins'] = Pin::valid()->ofType($type)->with('myClass')->latest()->get();
        $d['used_pins'] = Pin::used()->ofType($type)->with(['myClass', 'student', 'user'])->latest()->limit(100)->get();
        $d['classes'] = MyClass::orderBy('name')->get();
        $d['year'] = $this->year;
        $d['selected_type'] = $type;
        
        // Statistiques
        $d['stats'] = [
            'total_valid' => Pin::valid()->count(),
            'total_used' => Pin::used()->count(),
            'used_today' => Pin::used()->whereDate('updated_at', today())->count(),
            'revenue' => Pin::used()->sum('price'),
        ];
        
        return view('pages.support_team.pins.index', $d);
    }

    /**
     * Formulaire de création de PINs
     */
    public function create()
    {
        $d['classes'] = MyClass::with(['academicSection', 'option'])->orderBy('name')->get();
        $d['year'] = $this->year;
        
        return view('pages.support_team.pins.create', $d);
    }

    /**
     * Générer les PINs
     */
    public function store(Request $request)
    {
        $request->validate([
            'pin_count' => 'required|integer|min:1|max:100',
            'type' => 'required|in:bulletin,exam,result',
            'price' => 'nullable|numeric|min:0',
            'max_uses' => 'required|integer|min:1|max:10',
            'expires_days' => 'nullable|integer|min:1',
        ]);

        $num = (int) $request->pin_count;
        $expiresAt = $request->expires_days ? now()->addDays((int) $request->expires_days) : null;
        
        $data = [];
        for ($i = 0; $i < $num; $i++) {
            $code = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
            $data[] = [
                'code' => $code,
                'type' => $request->type,
                'year' => $request->year ?? $this->year,
                'period' => $request->period,
                'semester' => $request->semester,
                'my_class_id' => $request->my_class_id,
                'price' => $request->price ?? 0,
                'max_uses' => $request->max_uses,
                'expires_at' => $expiresAt,
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Pin::insert($data);
        
        return redirect()->route('pins.index')
            ->with('flash_success', "$num code(s) PIN généré(s) avec succès !");
    }

    /**
     * Page pour entrer un PIN (étudiant/parent)
     */
    public function enter_pin(Request $request)
    {
        if (Qs::userIsTeamSA()) {
            return redirect()->route('dashboard');
        }

        $d['type'] = $request->type ?? 'period';
        $d['period'] = $request->period ?? 1;
        $d['semester'] = $request->semester ?? 1;
        $d['redirect_url'] = $request->redirect_url;

        return view('pages.support_team.pins.enter', $d);
    }

    /**
     * Vérifier un PIN pour accéder au bulletin
     */
    public function verifyForBulletin(Request $request)
    {
        $request->validate([
            'pin_code' => 'required|string',
        ]);

        $user = Auth::user();
        $studentRecord = $user->student_record;
        
        if (!$studentRecord) {
            return back()->with('flash_danger', 'Profil étudiant non trouvé.');
        }

        $type = $request->type ?? 'period';
        $periodOrSemester = $type === 'period' ? ($request->period ?? 1) : ($request->semester ?? 1);
        
        // Chercher le PIN
        $pin = Pin::where('code', strtoupper($request->pin_code))->first();

        if (!$pin) {
            return back()->with('flash_danger', 'Code PIN invalide ou inexistant.');
        }

        // Vérifier si le PIN est valide pour ce bulletin
        if (!$pin->isValidForBulletin($type, $periodOrSemester, $this->year, $studentRecord->my_class_id)) {
            if (!$pin->isValid()) {
                return back()->with('flash_danger', 'Ce code PIN a déjà été utilisé ou a expiré.');
            }
            return back()->with('flash_danger', 'Ce code PIN n\'est pas valide pour ce bulletin.');
        }

        // Marquer le PIN comme utilisé
        $pin->markAsUsed($user->id, $user->id);

        // Sauvegarder en session que le PIN est vérifié
        $sessionKey = "pin_verified_{$type}_{$periodOrSemester}_{$this->year}";
        Session::put($sessionKey, true);

        // Rediriger vers le bulletin
        $redirectUrl = $request->redirect_url ?? route('student.grades.bulletin', [
            'type' => $type,
            'period' => $request->period ?? 1,
            'semester' => $request->semester ?? 1,
        ]);

        return redirect($redirectUrl)->with('flash_success', 'Code PIN validé ! Voici votre bulletin.');
    }

    /**
     * Vérification classique (compatibilité)
     */
    public function verify(Request $request, $student_id)
    {
        $request->validate(['pin_code' => 'required']);
        
        $user = Auth::user();
        $pin = Pin::where('code', strtoupper($request->pin_code))
            ->where(function ($q) use ($user, $student_id) {
                $q->where('used', 0)
                  ->orWhere(function ($q2) use ($user, $student_id) {
                      $q2->where('user_id', $user->id)
                         ->where('student_id', $student_id);
                  });
            })
            ->first();

        if ($pin && $pin->isValid()) {
            $pin->markAsUsed($user->id, $student_id);
            Session::put('pin_verified', $student_id);

            return Session::has('marks_url') 
                ? redirect(Session::get('marks_url')) 
                : redirect()->route('dashboard');
        }

        return back()->with('flash_danger', 'Code PIN invalide ou déjà utilisé.');
    }

    /**
     * Supprimer les PINs utilisés
     */
    public function destroy(Request $request)
    {
        $type = $request->type ?? 'all';
        
        if ($type === 'all') {
            $count = Pin::used()->delete();
        } else {
            $count = Pin::used()->ofType($type)->delete();
        }

        return back()->with('flash_success', "$count PIN(s) supprimé(s) avec succès.");
    }

    /**
     * Exporter les PINs en CSV
     */
    public function export(Request $request)
    {
        $type = $request->type ?? 'bulletin';
        $pins = Pin::valid()->ofType($type)->get();

        $filename = "pins_{$type}_" . date('Y-m-d_His') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($pins) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Code PIN', 'Type', 'Année', 'Période', 'Semestre', 'Prix', 'Expire le']);
            
            foreach ($pins as $pin) {
                fputcsv($file, [
                    $pin->code,
                    $pin->type,
                    $pin->year,
                    $pin->period,
                    $pin->semester,
                    $pin->price,
                    $pin->expires_at ? $pin->expires_at->format('d/m/Y') : 'Jamais',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Vérifier si un PIN est requis pour voir le bulletin
     */
    public static function isPinRequired(): bool
    {
        return Qs::getSetting('pin_required_for_bulletin', 'no') === 'yes';
    }

    /**
     * Vérifier si l'utilisateur a déjà vérifié un PIN pour ce bulletin
     */
    public static function hasPinVerified($type, $periodOrSemester, $year): bool
    {
        $sessionKey = "pin_verified_{$type}_{$periodOrSemester}_{$year}";
        return Session::has($sessionKey);
    }

    /**
     * Activer/Désactiver l'obligation du PIN pour les bulletins
     */
    public function toggleRequired()
    {
        $current = Qs::getSetting('pin_required_for_bulletin', 'no');
        $newValue = $current === 'yes' ? 'no' : 'yes';
        
        \App\Models\Setting::updateOrCreate(
            ['type' => 'pin_required_for_bulletin'],
            ['description' => $newValue]
        );

        $message = $newValue === 'yes' 
            ? 'Code PIN maintenant OBLIGATOIRE pour voir les bulletins.' 
            : 'Code PIN DÉSACTIVÉ. Les étudiants peuvent voir les bulletins sans PIN.';

        return back()->with('flash_success', $message);
    }
}
