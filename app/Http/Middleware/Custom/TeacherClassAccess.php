<?php

namespace App\Http\Middleware\Custom;

use App\Helpers\TeacherAccess;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherClassAccess
{
    /**
     * Vérifie que le professeur a accès à la classe demandée
     * Usage: Route::middleware('teacher.class:class_id')
     */
    public function handle(Request $request, Closure $next, $paramName = 'class_id')
    {
        $user = Auth::user();
        
        // Admin/SuperAdmin passent toujours
        if (in_array($user->user_type, ['super_admin', 'admin'])) {
            return $next($request);
        }

        // Récupérer l'ID de la classe depuis les paramètres ou la requête
        $classId = $request->route($paramName) ?? $request->input($paramName) ?? $request->input('my_class_id');

        if (!$classId) {
            return $next($request);
        }

        // Vérifier l'accès
        if (!TeacherAccess::canAccessClass($classId)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Accès non autorisé à cette classe'], 403);
            }
            return redirect()->back()->with('flash_danger', '❌ Vous n\'avez pas accès à cette classe.');
        }

        return $next($request);
    }
}
