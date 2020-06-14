<?php

namespace App\Http\Controllers\Api;

use App\Agent;
use App\Career;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
use App\NatureOfIncome;
use App\PollingBooth;
use App\Position;
use App\Religion;
use App\User;
use App\UserSociety;
use App\UserTitle;
use App\Village;
use App\VotersCount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiProfileController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $validationMessages = [
            'lang.required' => 'Please provide user language!',
            'lang.in' => 'User language invalid!',
        ];

        $validator = \Validator::make($request->all(), [
            'lang' => 'required|in:en,si,ta',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }

        $apiLang = $request['lang'];
        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }

        if($user->iduser_role == 6){
            $voting = VotersCount::where('idoffice',Auth::user()->idoffice)->where('idvillage',Auth::user()->agent->idvillage)->select(['total','forecasting','houses'])->first();
            if($voting == null){
                $voting['total'] = 0;
                $voting['forecasting'] = 0;
                $voting['houses'] = 0;

            }
            return response()->json(['success' => ['referral_code'=>Auth::user()->agent->referral_code,'village_meta'=>$voting,'completedPercentage'=>$this->completedPercentage()], 'statusCode' => 0]);
        }
        else if($user->iduser_role == 7){
            return response()->json(['success' => [], 'statusCode' => 0]);
        }
        else{
            return response()->json(['error' => 'Unknown user role', 'statusCode' => -99]);
        }
    }

    public function myProfile(Request $request){
        $validationMessages = [
            'lang.required' => 'Please provide user language!',
            'lang.in' => 'User language invalid!',
        ];

        $validator = \Validator::make($request->all(), [
            'lang' => 'required|in:en,si,ta',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }

        $apiLang = $request['lang'];
        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }

        if (Auth::user()->iduser_role == 6 || Auth::user()->iduser_role == 7 ) {

            $user['title'] =  Auth::user()->iduser_title;
            $user['firstName'] =  Auth::user()->fName;
            $user['nic'] =  Auth::user()->nic;
            $user['gender'] =  Auth::user()->gender;
            $user['contact_no1'] =  Auth::user()->contact_no1;
            $user['email'] =  Auth::user()->email;
            $user['username'] =  Auth::user()->username;
            $user['bday'] =  Auth::user()->bday;
            $user['userRole'] =  Auth::user()->iduser_role;
            $user['userTitle'] =  Auth::user()->iduser_title;
            $user['isGovernment'] =  Auth::user()->isGovernment;
            $user['youthSociety'] =  UserSociety::where('idUser',Auth::user()->idUser)->where('idsociety',3)->first() ? UserSociety::where('idUser',Auth::user()->idUser)->where('idsociety',3)->first()->idposition : null;
            $user['womenSociety'] =  UserSociety::where('idUser',Auth::user()->idUser)->where('idsociety',2)->first() ? UserSociety::where('idUser',Auth::user()->idUser)->where('idsociety',2)->first()->idposition : null;
            $user['branchSociety'] =  UserSociety::where('idUser',Auth::user()->idUser)->where('idsociety',1)->first() ? UserSociety::where('idUser',Auth::user()->idUser)->where('idsociety',1)->first()->idposition : null;

            if(Auth::user()->iduser_role == 6){

                $user['ethnicity'] =  Auth::user()->agent->idelection_division;
                $user['religion'] =  Auth::user()->agent->idreligion;
                $user['educationalQualification'] =  Auth::user()->agent->ideducational_qualification;
                $user['natureOfIncome'] =  Auth::user()->agent->idnature_of_income;
                $user['career'] =  Auth::user()->agent->idcareer;
            }
            else if(Auth::user()->iduser_role == 7){

                $user['ethnicity'] =  Auth::user()->member->idelection_division;
                $user['religion'] =  Auth::user()->member->idreligion;
                $user['educationalQualification'] =  Auth::user()->member->ideducational_qualification;
                $user['natureOfIncome'] =  Auth::user()->member->idnature_of_income;
                $user['career'] =  Auth::user()->member->idcareer;
            }
        }
        else{
            return response()->json(['error' => 'User invalid!', 'statusCode' => -99]);

        }
        return response()->json(['success' => $user,'statusCode'=>0]);

    }

    public function updateProfile(Request $request){

            //validation start
            $validator = \Validator::make($request->all(), [
                'title' => 'nullable|numeric',
                'firstName' => 'required',
                'isGovernment' => 'nullable',
                'gender' => 'nullable|numeric',
                'email' => 'nullable|email|max:255',
                'phone' => 'required|numeric',
                'dob' => 'nullable|date|before:today',
                'ethnicity' => 'nullable|exists:ethnicity,idethnicity',
                'religion' => 'nullable|exists:religion,idreligion',
                'career' => 'nullable|exists:career,idcareer',
                'educationalQualification' => 'nullable|exists:educational_qualification,ideducational_qualification',
                'natureOfIncome' => 'nullable|exists:nature_of_income,idnature_of_income',
                'branchSociety' => 'nullable|numeric',
                'womenSociety' => 'nullable|numeric',
                'youthSociety' => 'nullable|numeric',

            ], [
                'title.required' => 'User title should be provided!',
                'title.numeric' => 'User title invalid!',
                'firstName.required' => 'First name should be provided!',
                'firstName.regex' => 'First name can only contain characters!',
                'firstName.max' => 'First name must be less than 50 characters!',
                'email.email' => 'Email format invalid!',
                'email.max' => 'Email must be less than 255 characters!',
                'isGovernment.required' => 'Job sector should be provided!',
                'phone.numeric' => 'Phone number can only contain numbers!',
                'userRole.required' => 'User role should be provided!',
                'userRole.exists' => 'User role invalid!',
                'dob.required' => 'Date of birth should be provided!',
                'dob.date' => 'Date of birth format invalid!',
                'dob.before' => 'Date of birth should be a valid birthday!',
                'gender.required' => 'Gender should be provided!',
                'gender.numeric' => 'Gender invalid!',
                'ethnicity.required' => 'Ethnicity should be provided!',
                'ethnicity.exists' => 'Ethnicity invalid!',
                'religion.required' => 'Religion should be provided!',
                'religion.exists' => 'Religion invalid!',
                'career.required' => 'Career should be provided!',
                'career.exists' => 'Career invalid!',
                'educationalQualification.required' => 'Educational qualification should be provided!',
                'educationalQualification.exists' => 'Educational qualification invalid!',
                'natureOfIncome.required' => 'Nature of income should be provided!',
                'natureOfIncome.exists' => 'Nature of income invalid!',
                'branchSociety.numeric' => 'Branch society invalid!',
                'womenSociety.numeric' => 'Women\'s society invalid!',
                'youthSociety.numeric' => 'Youth invalid!',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }
        $office = Auth::user()->idoffice;
        $parentOffice = Auth::user()->office;
        $district = $parentOffice->iddistrict;

        if (Auth::user()->iduser_role == 6) {

//            if ($request['electionDivision'] == null) {
//                return response()->json(['error' => 'Election division should be provided!', 'statusCode' => -99]);
//            } else if (ElectionDivision::where('idelection_division', $request['electionDivision'])->where('iddistrict', $district)->first() == null) {
//                return response()->json(['error' => ['electionDivision' => 'Election division invalid!']]);
//            }
//            if ($request['pollingBooth'] == null) {
//                return response()->json(['error' => 'Polling booth should be provided!', 'statusCode' => -99]);
//            } else if (PollingBooth::where('idpolling_booth', $request['pollingBooth'])->where('iddistrict', $district)->first() == null) {
//                return response()->json(['error' => 'Polling booth invalid!', 'statusCode' => -99]);
//            }
//            if ($request['gramasewaDivision'] == null) {
//                return response()->json(['error' => 'Gramasewa division should be provided!', 'statusCode' => -99]);
//            } else if (GramasewaDivision::where('idgramasewa_division', $request['gramasewaDivision'])->where('iddistrict', $district)->first() == null) {
//                return response()->json(['error' => 'Gramasewa division invalid!', 'statusCode' => -99]);
//            }
//            if ($request['village'] == null) {
//                return response()->json(['error' => 'Village should be provided!', 'statusCode' => -99]);
//            } else if (Village::where('idvillage', $request['village'])->where('iddistrict', $district)->first() == null) {
//                return response()->json(['error' => 'Village invalid!', 'statusCode' => -99]);
//            }
        } else if (Auth::user()->iduser_role == 7) {


        } else {
            return response()->json(['error' => 'User role unknown!', 'statusCode' => -99]);
        }

        $request = app(ApiRegistrationController::class)->customRegistrationValidation($request);

        if (User::where('nic',$request['nic'])->where('idUser','!=',Auth::user()->idUser)->first() != null){
            return response()->json(['error' => 'NIC No already exist!', 'statusCode' => -99]);
        }
        if (User::where('username',$request['username'])->where('idUser','!=',Auth::user()->idUser)->first() != null){
            return response()->json(['error' => 'Username already exist!', 'statusCode' => -99]);
        }

        //---------------------------------------------validation end-------------------------------------------------//

        //save in user table
        $user = Auth::user();
        $user->idoffice = $office;
        $user->iduser_role = $request['userRole'];
        $user->iduser_title = $request['title'];
        $user->fName = $request['firstName'];
        $user->lName = $request['lastName'];
        $user->nic = $request['nic'];
        $user->gender = $request['gender'];
        $user->contact_no1 = $request['phone'];
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->bday = $request['dob'];
        $user->save();
        //save in user table  end

        //save in selected user role table
        if ($user->iduser_role == 6) {
            $this->updateAgent($request);

        } else if ($user->iduser_role == 7) {
            $this->updateMember($request);
        }
        // save in selected user role table end

        return response()->json(['success' => 'Profile updated successfully!', 'statusCode' => 0]);

    }

    public function updateAgent($request){

        $agent = Auth::user()->agent;
//        $agent->idelection_division = $request['electionDivision'];
//        $agent->idpolling_booth = $request['pollingBooth'];
//        $agent->idgramasewa_division = $request['gramasewaDivision'];
//        $agent->idvillage = $request['village'];
        $agent->idethnicity = $request['ethnicity'];
        $agent->idreligion = $request['religion'];
        $agent->ideducational_qualification = $request['educationalQualification'];
        $agent->idnature_of_income = $request['natureOfIncome'];
        $agent->idcareer = $request['career'];
        $agent->homeNo = $request['houseNo'];
        $agent->is_government = $request['isGovernment'];
        $agent->save();

    }

    public function updateMember($request){

        $member = Auth::user()->member;
        $member->idethnicity = $request['ethnicity'];
        $member->idreligion = $request['religion'];
        $member->ideducational_qualification = $request['educationalQualification'];
        $member->idnature_of_income = $request['natureOfIncome'];
        $member->idcareer = $request['career'];
        $member->is_government = $request['isGovernment'];
        $member->homeNo = $request['houseNo'];
        $member->save();

    }

    public function editForm(Request $request){
        $apiLang = $request['lang'];
        $fallBack = 'name_en';


        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }

        $titles = UserTitle::select(['iduser_title', 'name_en', $lang, 'gender'])->get();
        $titles = app(ApiUserController::class)->filterLanguage($titles, $lang, $fallBack, 'iduser_title');

        $careers = Career::select(['idcareer', 'name_en', $lang])->get();
        $careers = app(ApiUserController::class)->filterLanguage($careers, $lang, $fallBack, 'idcareer');

        $ethnicities = Ethnicity::select(['idethnicity', 'name_en', $lang])->get();
        $ethnicities = app(ApiUserController::class)->filterLanguage($ethnicities, $lang, $fallBack, 'idethnicity');

        $religions = Religion::select(['idreligion', 'name_en', $lang])->get();
        $religions = app(ApiUserController::class)->filterLanguage($religions, $lang, $fallBack, 'idreligion');

        $educationQualifications = EducationalQualification::select(['ideducational_qualification', 'name_en', $lang])->get();
        $educationQualifications = app(ApiUserController::class)->filterLanguage($educationQualifications, $lang, $fallBack, 'ideducational_qualification');

        $natureOfIncomes = NatureOfIncome::select(['idnature_of_income', 'name_en', $lang])->get();
        $natureOfIncomes = app(ApiUserController::class)->filterLanguage($natureOfIncomes, $lang, $fallBack, 'idnature_of_income');

        $positions = Position::select(['idposition', $lang, 'name_en'])->get();
        $positions = app(ApiUserController::class)->filterLanguage($positions, $lang, $fallBack, 'idposition');


        return response()->json(['success' =>
            [   'titles' => $titles,
                'careers' => $careers,
                'ethnicities' => $ethnicities,
                'religions' => $religions,
                'educationQualifications' => $educationQualifications,
                'natureOfIncomes' => $natureOfIncomes,
                'positions'=>$positions
            ], 'statusCode' => 0], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);

    }

    public function completedPercentage(){
        $educationDefault = EducationalQualification::where('name_en','UNDISCLOSED')->where('status',0)->first()->ideducational_qualification;
        $religionDefault = Religion::where('name_en','UNDISCLOSED')->where('status',0)->first()->idreligion;
        $ethnicityDefault = Ethnicity::where('name_en','UNDISCLOSED')->where('status',0)->first()->idethnicity;
        $careerDefault  = Career::where('name_en','UNDISCLOSED')->where('status',0)->first()->idcareer;
        $incomeDefault  = NatureOfIncome::where('name_en','UNDISCLOSED')->where('status',0)->first()->idnature_of_income;
        $titleDefault  = UserTitle::where('name_en','')->where('status',0)->first()->iduser_title;

        $mark = 0;
        $total = 0;

        if(Auth::user()->gender != 4){
            $mark +=1;
        }
        $total += 1;

        if(Auth::user()->email != null && Auth::user()->email != ''){
            $mark +=1;
        }
        $total += 1;

        if(Auth::user()->bday != null){
            $mark +=1;
        }
        $total += 1;

        if(Auth::user()->iduser_title != $titleDefault){
            $mark +=1;
        }
        $total += 1;


        if(Auth::user()->iduser_role == 6){

            $agent = Auth::user()->agent;

            if($agent->idethnicity != $ethnicityDefault){
                $mark +=1;
            }
            $total += 1;

            if($agent->idreligion != $religionDefault){
                $mark +=1;
            }
            $total += 1;

            if($agent->ideducational_qualification != $educationDefault){
                $mark +=1;
            }
            $total += 1;

            if($agent->idcareer != $careerDefault ){
                $mark +=1;
            }
            $total += 1;

            if($agent->idnature_of_income != $incomeDefault ){
                $mark +=1;
            }
            $total += 1;

            if($agent->homeNo != null ){
                $mark +=1;
            }
            $total += 1;

            if($agent->is_government != 4 ){
                $mark +=1;
            }
            $total += 1;

        }
        if(Auth::user()->iduser_role == 7){

            $member = Auth::user()->member;

            if($member->idethnicity != $ethnicityDefault){
                $mark +=1;
            }
            $total += 1;

            if($member->idreligion != $religionDefault){
                $mark +=1;
            }
            $total += 1;

            if($member->ideducational_qualification != $educationDefault){
                $mark +=1;
            }
            $total += 1;

            if($member->idcareer != $careerDefault ){
                $mark +=1;
            }
            $total += 1;

            if($member->idnature_of_income != $incomeDefault ){
                $mark +=1;
            }
            $total += 1;

            if($member->homeNo != null ){
                $mark +=1;
            }
            $total += 1;

            if($member->is_government != 4 ){
                $mark +=1;
            }
            $total += 1;

        }

        return round($mark/$total*100);
    }
}
