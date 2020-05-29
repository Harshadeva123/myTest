<?php

namespace App\Http\Controllers;

use App\ElectionDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * @return $this
     */
    public function index(){
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->latest()->get();
        return view('event.create')->with(['title'=>'Create Event','electionDivisions'=>$electionDivisions]);

    }

    public function view(Request $request){

    }

    public function store(Request $request){

        $validator = \Validator::make($request->all(), [
            'event_en' => 'required',
            'location_en' => 'required',
            'date' => 'required',
            'time' => 'required'

        ], [
            'event_en.required' => 'Event name  should be provided!',
            'location_en.required' => 'Event location should be provided!',
            'date.required' => 'Event date should be provided!',
            'time.required' => 'Election division (Tamil) should be provided!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        return response()->json(['errors' => ['error'=>'suceess']]);
    }
}
