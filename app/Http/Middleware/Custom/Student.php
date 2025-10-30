<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Qs;

class Student
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            \Log::info('Redirection vers login: Utilisateur non connecté');
            return redirect()->route('login');
        }

        $user = Auth::user();
        \Log::info("Vérification de l'utilisateur", ['user_id' => $user->id, 'user_type' => $user->user_type]);
        
        // Vérifier si l'utilisateur est un étudiant
        if ($user->user_type !== 'student') {
            \Log::warning("Accès refusé: L'utilisateur n'est pas un étudiant", ['user_id' => $user->id]);
            return redirect()->route('dashboard')
                ->with('error', 'Accès refusé. Réservé aux étudiants.');
        }

        // Vérifier si l'utilisateur a un enregistrement étudiant
        if (!$user->student) {
            try {
                // Vérifier d'abord s'il existe un enregistrement dans student_records
                $studentRecord = \App\Models\StudentRecord::where('user_id', $user->id)->first();
                
                if ($studentRecord) {
                    // Créer un enregistrement dans la table students à partir de student_records
                    $student = new \App\Models\Student();
                    $student->user_id = $user->id;
                    $student->my_class_id = $studentRecord->my_class_id;
                    $student->section_id = $studentRecord->section_id;
                    $student->admission_number = $studentRecord->adm_no;
                    $student->save();
                } else {
                    // Créer un enregistrement étudiant avec les champs obligatoires
                    $student = new \App\Models\Student();
                    $student->user_id = $user->id;
                    $student->admission_number = 'STD' . $user->id . time();
                    $student->save();
                }
                
                // Rafraîchir la relation
                $user->load('student');
                \Log::info('Nouvel enregistrement étudiant créé', ['user_id' => $user->id]);
                
            } catch (\Exception $e) {
                \Log::error('Erreur création étudiant: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Continuer quand même pour ne pas bloquer l'utilisateur
                $user->load('student');
            }
        }

        return $next($request);
    }

}
