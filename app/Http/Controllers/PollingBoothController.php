<?php

namespace App\Http\Controllers;

use App\District;
use App\ElectionDivision;
use App\PollingBooth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollingBoothController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get();
        return view('polling_booth.add')->with(['title'=>'Polling Booth','electionDivisions'=>$electionDivisions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByAuth()
    {
        $district  = intval(Auth::user()->office->iddistrict);
        $district = District::with(['electionDivisions.pollingBooths'])->where('iddistrict',$district)->where('status',1)->first();
        return response()->json(['success'  => $district]);
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
            'electionDivision' => 'required|exists:election_division,idelection_division',
            'pollingBooth' => 'required|max:100',
            'pollingBooth_si' => 'required|max:100',
            'pollingBooth_ta' => 'required|max:100'

        ], [
            'electionDivision.required' => 'Election Division should be provided!',
            'electionDivision.exists' => 'Election Division invalid!',
            'pollingBooth.required' => 'Polling Booth should be provided!',
            'pollingBooth_si.required' => 'Polling Booth (Sinhala) should be provided!',
            'pollingBooth_ta.required' => 'Polling Booth (Tamil) should be provided!',
            'pollingBooth.max' => 'Polling Booth should be less than 100 characters long!',
            'pollingBooth_si.max' => 'Polling Booth (Sinhala) should be less than 100 characters long!',
            'pollingBooth_ta.max' => 'Polling Booth (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //validation end

        $division = new PollingBooth();
        $division->idelection_division = $request['electionDivision'];
        $division->name_en = $request['pollingBooth'];
        $division->name_si = $request['pollingBooth_si'];
        $division->name_ta = $request['pollingBooth_ta'];
        $division->status = 1;
        $division->save();
        return response()->json(['success' => 'Polling Booth saved']);
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
