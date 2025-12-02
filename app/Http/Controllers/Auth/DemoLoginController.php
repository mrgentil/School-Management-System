<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DemoLoginController extends Controller
{
    /**
     * Credentials and redirects for demo accounts
     */
    protected $demoAccounts = [
        'admin' => [
            'email' => 'admin@admin.com', 
            'password' => 'cj',
            'redirect' => '/dashboard'
        ],
        'teacher' => [
            'email' => 'teacher@teacher.com', 
            'password' => 'cj',
            'redirect' => '/teacher/dashboard'
        ],
        'student' => [
            'email' => 'etudiant1@example.com', 
            'password' => 'password',
            'redirect' => '/student/dashboard'
        ],
        'parent' => [
            'email' => 'parent@parent.com', 
            'password' => 'cj',
            'redirect' => '/parent/dashboard'
        ],
        'accountant' => [
            'email' => 'accountant@accountant.com', 
            'password' => 'cj',
            'redirect' => '/accountant/dashboard'
        ],
        'librarian' => [
            'email' => 'librarian@librarian.com', 
            'password' => 'cj',
            'redirect' => '/librarian/dashboard'
        ],
    ];

    /**
     * Login as demo user
     */
    public function login($role)
    {
        // Validate role
        if (!array_key_exists($role, $this->demoAccounts)) {
            return redirect()->route('login')->with('error', 'Rôle de démonstration invalide.');
        }

        $account = $this->demoAccounts[$role];

        // Attempt login
        if (Auth::attempt(['email' => $account['email'], 'password' => $account['password']])) {
            request()->session()->regenerate();
            return redirect($account['redirect']);
        }

        return redirect()->route('login')->with('error', 'Compte de démonstration non disponible.');
    }
}
