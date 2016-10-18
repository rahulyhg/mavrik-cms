<?php

namespace App\Http\Controllers;

use App\UploadPreLaunch;
use Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $launch;
    
    
    public function __construct(UploadPreLaunch $launch)
    {
        $this->launch = $launch;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    public function upload_pre_launch()
    {
        return $this->launch->set();
    }

    /**
     * Logout Atuthenticated User.
     *
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/auth/login');
    }
}
