<?php

namespace App\Http\Controllers\Api;

use App\VotersCount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiInitialSetupController extends Controller
{
    public function markVillage(Request $request){
        $validator = \Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ], [
            'lat.required' => 'Latitude should be provided!',
            'lat.numeric' => 'Latitude invalid!',
            'long.required' => 'Longitude should be provided!',
            'long.numeric' => 'Longitude invalid!',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        $voters = VotersCount::where('idoffice',Auth::user()->idoffice)->where('idvillage',Auth::user()->agent->idvillage)->where('status',1)->first();
        if($voters != null){
            $voters->idUser = Auth::user()->idUser;
            $voters->lat= $request['lat'];
            $voters->long= $request['long'];
            $voters->update();
        }
        else{
            $voters = new VotersCount();
            $voters->idvillage = Auth::user()->agent->idvillage;
            $voters->idoffice = Auth::user()->idoffice;
            $voters->idUser = Auth::user()->idUser;
            $voters->lat= $request['lat'];
            $voters->long= $request['long'];
            $voters->total= 0;
            $voters->forecasting= 0;
            $voters->houses= 0;
            $voters->status  = 1;
            $voters->save();
        }

        return response()->json(['success' => 'Location saved', 'statusCode' => 0]);

    }
}
