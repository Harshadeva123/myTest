<?php

namespace App\Http\Controllers;

use App\ElectionDivision;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index(){
        $electionDivisions = ElectionDivision::where('status', 1)->where('iddistrict', Auth::user()->office->iddistrict)->get();

        $users = User::where('idoffice',Auth::user()->idoffice)->where('iduser_role',5)->where('status',1)->paginate(10);
        return view('staff.assign_staff')->with(['title'=>'Assign Staff','users'=>$users,'electionDivisions'=>$electionDivisions]);

    }
}
