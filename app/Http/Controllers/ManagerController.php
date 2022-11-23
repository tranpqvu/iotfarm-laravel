<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Hash;
use App\Util;
use App\PeerAssessment;

class ManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('manager');
        date_default_timezone_set(Util::$time_zone);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
       
        if ( $user->level == 0 || $user->level== 1 ){
            return view('admin.admin_home');
        }
        return redirect('/');
    }



}
