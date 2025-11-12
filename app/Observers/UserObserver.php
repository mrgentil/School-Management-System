<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Student;

class UserObserver
{
    public function created(User $user)
    {
        // Vérifier si l'utilisateur est un étudiant
        if ($user->user_type === 'student') {
            // Créer automatiquement l'étudiant
            $student = new Student();
            $student->user_id = $user->id;
            $student->adm_no = 'ADM' . $user->id . date('Ymd');
            $student->year_admitted = date('Y');
            $student->save();
        }
    }

    public function updated(User $user)
    {
        // Si le type d'utilisateur est changé en "student"
        if ($user->isDirty('user_type') && $user->user_type === 'student') {
            // Vérifier si l'étudiant n'existe pas déjà
            if (!$user->student) {
                $student = new Student();
                $student->user_id = $user->id;
                $student->adm_no = 'ADM' . $user->id . date('Ymd');
                $student->year_admitted = date('Y');
                $student->save();
            }
        }
    }
}