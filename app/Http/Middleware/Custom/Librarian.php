<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Auth;

class Librarian
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
        // Accès: Librarian + Super Admin + Admin
        if (Auth::check() && (Qs::userIsLibrarian() || Qs::userIsTeamSA())) {
            return $next($request);
        }
        
        return redirect()->route('login');
    }
}
