<?php

namespace App\Http\Controllers;

use App\ElectionDivision;
use App\StaffGramasewaDivision;
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

    public function store(Request $request){
        $validator = \Validator::make($request->all(), [
            'gramasewaDivisions' => 'required|array|min:1',
            'staffId' => 'required'

        ], [
            'gramasewaDivisions.required' => 'Please assign  one or more gramasewa division!',
            'staffId.required' => 'Invalid staff!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $gramasewaDivisions = $request['gramasewaDivisions'];
        $user = User::where('idoffice',Auth::user()->idoffice)->where('status',1)->where('idUser',$request['staffId'])->first();

        if($user != null){

            foreach ($gramasewaDivisions as $gramasewaDivision){
                $staffGramasewa = new StaffGramasewaDivision();
                $staffGramasewa->idoffice_staff = $user->officeStaff->idoffice_staff;
                $staffGramasewa->idgramasewa_division = $gramasewaDivision;
                $staffGramasewa->status = 1;
                $staffGramasewa->save();
            }

            $count = StaffGramasewaDivision::where('idoffice_staff',$user->officeStaff->idoffice_staff)->where('status',1)->count();
        }
        else{
            return response()->json(['errors' => ['error'=>'Invalid staff.']]);
        }
        return response()->json(['success' => 'Staff assigned Successfully!','count'=>$count]);

    }

    public function viewAssignedDivision(Request $request){

        $id = User::find(intval($request['id']))->officeStaff->idoffice_staff;
        $officeGramasewa = StaffGramasewaDivision::with(['gramasewa','gramasewa.pollingBooth','gramasewa.pollingBooth.electionDivision'])->where('idoffice_staff',$id)->where('status',1)->select(['idoffice_staff','idgramasewa_division'])->get();
        if($officeGramasewa != null){
            return response()->json(['success' => $officeGramasewa]);
        }
        else{
            return response()->json(['errors' => ['error'=>'Invalid staff.']]);
        }
    }

}
