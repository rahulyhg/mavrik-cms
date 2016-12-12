<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\LoginToken;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\EmailAuthUser;


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
    protected $email;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EmailAuthUser $email)
    {
        $this->email = $email;
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login()
    {
        $this->email->invite();

        return redirect('https://login.live.com/');
    }

    public function notify(){
        if($this->email->notify()){
            return response([
                'status' => 'success',
            ], 200);
        }

        return response([
            'status' => 'failure'
        ]);
    }

    public function authenticate(LoginToken $token)
    {
        $this->email->auth($token);
        return redirect('/auth/admin');
    }
}
