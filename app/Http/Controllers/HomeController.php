<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\UserRepo;

class HomeController extends Controller
{
    protected $user;
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }


    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.terms_of_use', $data);
    }

    public function dashboard()
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $userType = auth()->user()->user_type;
        
        // Rediriger selon le type d'utilisateur
        switch ($userType) {
            case 'super_admin':
                return redirect()->route('super_admin.dashboard');
            case 'student':
                return redirect()->route('student.dashboard');
            case 'librarian':
                return redirect()->route('librarian.dashboard');
            case 'accountant':
                return redirect()->route('accountant.dashboard');
            case 'parent':
                return redirect()->route('parent.dashboard');
            case 'teacher':
                return redirect()->route('teacher.dashboard');
        }
        
        // Pour admin et teacher, afficher le dashboard support_team
        $d = [];
        if (Qs::userIsTeamSAT()) {
            $d['users'] = $this->user->getAll();
        }

        return view('pages.support_team.dashboard', $d);
    }
}
