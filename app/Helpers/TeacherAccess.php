<?php

namespace App\Helpers;

use App\Models\Subject;
use App\Models\MyClass;
use Illuminate\Support\Facades\Auth;

class TeacherAccess
{
    /**
     * Obtenir les IDs des classes où le professeur enseigne
     */
    public static function getTeacherClassIds($teacherId = null)
    {
        $teacherId = $teacherId ?? Auth::id();
        
        return Subject::where('teacher_id', $teacherId)
            ->distinct()
            ->pluck('my_class_id')
            ->toArray();
    }

    /**
     * Obtenir les IDs des matières du professeur
     */
    public static function getTeacherSubjectIds($teacherId = null)
    {
        $teacherId = $teacherId ?? Auth::id();
        
        return Subject::where('teacher_id', $teacherId)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Vérifier si le professeur a accès à une classe
     */
    public static function canAccessClass($classId, $teacherId = null)
    {
        $teacherId = $teacherId ?? Auth::id();
        
        // Admin/SuperAdmin ont accès à tout
        if (in_array(Auth::user()->user_type, ['super_admin', 'admin'])) {
            return true;
        }

        // Vérifier si le prof enseigne dans cette classe
        return Subject::where('teacher_id', $teacherId)
            ->where('my_class_id', $classId)
            ->exists();
    }

    /**
     * Vérifier si le professeur a accès à une matière
     */
    public static function canAccessSubject($subjectId, $teacherId = null)
    {
        $teacherId = $teacherId ?? Auth::id();
        
        // Admin/SuperAdmin ont accès à tout
        if (in_array(Auth::user()->user_type, ['super_admin', 'admin'])) {
            return true;
        }

        return Subject::where('id', $subjectId)
            ->where('teacher_id', $teacherId)
            ->exists();
    }

    /**
     * Vérifier si le professeur peut noter un élève (via la matière et la classe)
     */
    public static function canGradeStudent($studentClassId, $subjectId, $teacherId = null)
    {
        $teacherId = $teacherId ?? Auth::id();
        
        // Admin/SuperAdmin ont accès à tout
        if (in_array(Auth::user()->user_type, ['super_admin', 'admin'])) {
            return true;
        }

        return Subject::where('id', $subjectId)
            ->where('my_class_id', $studentClassId)
            ->where('teacher_id', $teacherId)
            ->exists();
    }

    /**
     * Obtenir les classes du professeur avec les matières
     */
    public static function getTeacherClassesWithSubjects($teacherId = null)
    {
        $teacherId = $teacherId ?? Auth::id();
        
        $subjects = Subject::where('teacher_id', $teacherId)
            ->with('my_class')
            ->get();

        return $subjects->groupBy('my_class_id')->map(function($items) {
            return [
                'class' => $items->first()->my_class,
                'subjects' => $items,
            ];
        });
    }

    /**
     * Vérifier si l'utilisateur est un professeur
     */
    public static function isTeacher($userId = null)
    {
        $user = $userId ? \App\Models\User::find($userId) : Auth::user();
        return $user && $user->user_type === 'teacher';
    }
}
