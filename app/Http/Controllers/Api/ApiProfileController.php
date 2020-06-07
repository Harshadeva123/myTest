<?php

namespace App\Http\Controllers\Api;

use App\VotersCount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiProfileController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();

        if($user->iduser_role == 6){
            $voting = VotersCount::where('idoffice',Auth::user()->idoffice)->select(['total','forecasting','houses'])->first();
            return response()->json(['success' => ['referral_code'=>Auth::user()->agent->referral_code,'village_meta'=>$voting], 'statusCode' => 0]);
        }
        else if($user->iduser_role == 7){
            return response()->json(['success' => [], 'statusCode' => 0]);
        }
        else{
            return response()->json(['error' => 'Unknown user role', 'statusCode' => -99]);
        }
    }
}
