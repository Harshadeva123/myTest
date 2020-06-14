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
            $voting = VotersCount::where('idoffice',Auth::user()->idoffice)->where('idvillage',Auth::user()->agent->idvillage)->select(['total','forecasting','houses'])->first();
            if($voting == null){
                $voting['total'] = 0;
                $voting['forecasting'] = 0;
                $voting['houses'] = 0;

            }
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
