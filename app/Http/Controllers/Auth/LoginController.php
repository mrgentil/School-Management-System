<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Get the post-login redirect path based on user type.
     *
     * @return string
     */
    public function redirectTo()
    {
        // Rediriger selon le type d'utilisateur
        if (auth()->check()) {
            $userType = auth()->user()->user_type;
            
            switch ($userType) {
                case 'student':
                    return '/student/dashboard';
                case 'teacher':
                    return '/dashboard';
                case 'admin':
                    return '/dashboard';
                case 'super_admin':
                    return '/dashboard';
                case 'parent':
                    return '/dashboard';
                default:
                    return '/dashboard';
            }
        }
        
        return $this->redirectTo;
    }

    /**
     * Create a new controller instance.
     *
     * @return void$field
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*
     *  Login with Username or Email
     * */
    public function username()
    {
        $identity = request()->identity;
        $field = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $identity]);
        return $field;
    }
}
