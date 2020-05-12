<?php

namespace App\Http\Controllers;

use App\ElectionDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElectionDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('election_division.add')->with(['title'=>'Election Division']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByAuth()
    {
        $district  = Auth::user()->office->iddistrict;
        $electionDivisions = ElectionDivision::where('iddistrict',$district)->where('status',1)->latest()->get();
        return response()->json(['success'  =>$electionDivisions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'electionDivision' => 'required|max:100',
            'electionDivision_si' => 'required|max:100',
            'electionDivision_ta' => 'required|max:100'

        ], [
            'electionDivision.required' => 'Election division should be provided!',
            'electionDivision_si.required' => 'Election division (Sinhala) should be provided!',
            'electionDivision_ta.required' => 'Election division (Tamil) should be provided!',
            'electionDivision.max' => 'Election division should be less than 100 characters long!',
            'electionDivision_si.max' => 'Election division (Sinhala) should be less than 100 characters long!',
            'electionDivision_ta.max' => 'Election division (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //validation end

        $division = new ElectionDivision();
        $division->iddistrict = Auth::user()->office->iddistrict;
        $division->name_en = strtoupper($request['electionDivision']);
        $division->name_si = $request['electionDivision_si'];
        $division->name_ta = $request['electionDivision_ta'];
        $division->status = 1;
        $division->save();
        return response()->json(['success' => 'Election division saved']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
