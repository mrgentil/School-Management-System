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
        // Rediriger les étudiants vers leur dashboard spécifique
        if (auth()->check() && auth()->user()->user_type === 'student') {
            return redirect()->route('student.dashboard');
        }
        
        // Rediriger les bibliothécaires vers leur dashboard spécifique
        if (auth()->check() && auth()->user()->user_type === 'librarian') {
            return redirect()->route('librarian.dashboard');
        }
        
        // Rediriger les comptables vers leur dashboard spécifique
        if (auth()->check() && auth()->user()->user_type === 'accountant') {
            return redirect()->route('accountant.dashboard');
        }
        
        $d=[];
        if(Qs::userIsTeamSAT()){
            $d['users'] = $this->user->getAll();
        }

        return view('pages.support_team.dashboard', $d);
    }
}
