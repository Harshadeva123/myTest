<?php

namespace App\Http\Controllers;

use App\Agent;
use App\ElectionDivision;
use App\GramasewaDivision;
use App\PollingBooth;
use App\Village;
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
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status','>=',1)->get();
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
        $pollingBooth = PollingBooth::with(['electionDivision'])->where('iddistrict',$district)->latest()->where('status','>=',1)->get();
        return response()->json(['success'  => $pollingBooth]);
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

        $booth = new PollingBooth();
        $booth->idelection_division = $request['electionDivision'];
        $booth->iddistrict = ElectionDivision::find(intval($request['electionDivision']))->iddistrict;
        $booth->name_en = $request['pollingBooth'];
        $booth->name_si = $request['pollingBooth_si'];
        $booth->name_ta = $request['pollingBooth_ta'];
        $booth->status = 1;
        $booth->idUser = Auth::user()->idUser;
        $booth->save();
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
    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'updateId' => 'required',
            'electionDivision' => 'required|exists:election_division,idelection_division',
            'pollingBooth' => 'required|max:100',
            'pollingBooth_si' => 'required|max:100',
            'pollingBooth_ta' => 'required|max:100'

        ], [
            'updateId.required' => 'Update process has failed!',
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
        $parentChanged = false;
        $booth = PollingBooth::find($request['updateId']);
        if($booth->idelection_division != $request['electionDivision']){
            $users = Agent::where('idpolling_booth',$request['updateId'])->first();
            if($users != null) {
                return response()->json(['errors' => ['electionDivision'=>'Election division can not be changed!']]);
            }
            $booth->idelection_division = $request['electionDivision'];
            $parentChanged = true;
        }
        //validation end
        $booth->name_en = $request['pollingBooth'];
        $booth->name_si = $request['pollingBooth_si'];
        $booth->name_ta = $request['pollingBooth_ta'];
        $booth->idUser = Auth::user()->idUser;
        $booth->save();

        //save in relation table
        if($parentChanged) {
            $gramasewaDivisions = GramasewaDivision::where('idpolling_booth', $booth->idpolling_booth)->get();
            if ($gramasewaDivisions != null) {
                $gramasewaDivisions->each(function ($item, $key) use ($request) {
                    $item->idelection_division = $request['electionDivision'];
                    $item->idUser = Auth::user()->idUser;
                    $item->save();

                    $villages = Village::where('idgramasewa_division', $item->idgramasewa_division)->get();
                    if ($villages != null) {
                        $villages->each(function ($item1, $key1) use ($item) {
                            $item1->idpolling_booth = $item->idpolling_booth;
                            $item1->idelection_division = $item->idelection_division;
                            $item1->idUser = Auth::user()->idUser;
                            $item1->save();
                        });
                    }
                });
            }
        }
        //save in relation table end

        return response()->json(['success' => 'Polling Booth updated']);
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
