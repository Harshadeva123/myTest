<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Career;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
use App\Member;
use App\NatureOfIncome;
use App\Office;
use App\OfficeAdmin;
use App\PollingBooth;
use App\Religion;
use App\User;
use App\UserTitle;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class ApiUserController extends Controller
{
    /**
     *add new user to database
     */
    public function store(Request $request)
    {

        //validation start
        $validator = \Validator::make($request->all(), [
            'userRole' => 'required|exists:user_role,iduser_role',
            'username' => 'required|max:50|unique:usermaster',
            'password' => 'required|confirmed',
            'title' => 'required|numeric',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required|boolean',
            'nic' => 'required|max:15|unique:usermaster',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|numeric',
            'dob' => 'required|date|before:today',
            'ethnicity' => 'required|exists:ethnicity,idethnicity',
            'religion' => 'required|exists:religion,idreligion',
            'career' => 'required|exists:career,idcareer',
            'educationalQualification' => 'required|exists:educational_qualification,ideducational_qualification',
            'natureOfIncome' => 'required|exists:nature_of_income,idnature_of_income',


        ], [
            'nic.required' => 'NIC No should be provided!',
            'nic.max' => 'NIC No invalid!',
            'nic.unique' => 'NIC No already exist!',
            'title.required' => 'User title should be provided!',
            'title.numeric' => 'User title invalid!',
            'firstName.required' => 'First name should be provided!',
            'firstName.regex' => 'First name can only contain characters!',
            'firstName.max' => 'First name must be less than 50 characters!',
            'lastName.required' => 'Last name should be provided!',
            'lastName.regex' => 'Last name can only contain characters!',
            'lastName.max' => 'Last name must be less than 50 characters!',
            'username.required' => 'Username should be provided!',
            'username.max' => 'Username must be less than 50 characters!',
            'username.unique' => 'Username already taken!',
            'email.email' => 'Email format invalid!',
            'email.max' => 'Email must be less than 255 characters!',
            'password.required' => 'Password should be provided!',
            'password.confirmed' => 'Passwords didn\'t match!',
            'phone.numeric' => 'Phone number can only contain numbers!',
            'userRole.required' => 'User role should be provided!',
            'userRole.exists' => 'User role invalid!',
            'dob.required' => 'Date of birth should be provided!',
            'dob.date' => 'Date of birth format invalid!',
            'dob.before' => 'Date of birth should be a valid birthday!',
            'gender.required' => 'Gender should be provided!',
            'gender.boolean' => 'Gender invalid!',
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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request['userRole'] == 6) {
            if ($request['referral_code'] == null) {
                return response()->json(['errors' => ['referral_code' => 'Office referral code should be provided!']]);
            }
            $officeAdmin = OfficeAdmin::where('referral_code', $request['referral_code'])->whereIn('status', [1, 2])->first();
            if ($officeAdmin == null) {
                return response()->json(['errors' => ['referral_code' => 'Referral code invalid!']]);
            }
            $office = $officeAdmin->user->idoffice;
            $district = Office::find(intval($office))->iddistrict;

            if ($request['electionDivision'] == null) {
                return response()->json(['errors' => ['electionDivision' => 'Election division should be provided!']]);
            } else if (ElectionDivision::where('idelection_division', $request['electionDivision'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['errors' => ['electionDivision' => 'Election division invalid!']]);
            }
            if ($request['pollingBooth'] == null) {
                return response()->json(['errors' => ['pollingBooth' => 'Polling booth should be provided!']]);
            } else if (PollingBooth::where('idpolling_booth', $request['pollingBooth'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['errors' => ['pollingBooth' => 'Polling booth invalid!']]);
            }
            if ($request['gramasewaDivision'] == null) {
                return response()->json(['errors' => ['gramasewaDivision' => 'Gramasewa division should be provided!']]);
            } else if (GramasewaDivision::where('idgramasewa_division', $request['gramasewaDivision'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['errors' => ['gramasewaDivision' => 'Gramasewa division invalid!']]);
            }
            if ($request['village'] == null) {
                return response()->json(['errors' => ['gramasewaDivision' => 'Village should be provided!']]);
            } else if (Village::where('idvillage', $request['village'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['errors' => ['village' => 'Village invalid!']]);
            }
        } else if ($request['userRole'] == 7) {
            if ($request['referral_code'] == null) {
                return response()->json(['errors' => ['referral_code' => 'Agent referral code should be provided!']]);
            }
            $agent = Agent::where('referral_code', $request['referral_code'])->whereIn('status', [1, 2])->first();
            if ($agent == null) {
                return response()->json(['errors' => ['referral_code' => 'Referral code invalid!']]);
            }

            $office = User::find(intval($agent->idUser))->idoffice;
            $district = Office::find(intval($office))->iddistrict;
        } else {
            return response()->json(['errors' => ['userRole' => 'User role unknown!']]);
        }

        if (isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender']) {
            return response()->json(['errors' => ['title' => 'Please re-check title and gender!']]);
        }

        //validation end

        //save in user table
        $user = new User();
        $user->idoffice = $office;
        $user->iduser_role = $request['userRole'];
        $user->iduser_title = $request['title'];
        $user->fName = $request['firstName'];
        $user->lName = $request['lastName'];
        $user->nic = $request['nic'];
        $user->gender = $request['gender'];
        $user->address = $request['address'];
        $user->contact_no1 = $request['phone'];
        $user->contact_no2 = null;
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->password = Hash::make($request['password']);
        $user->bday = date('Y-m-d', strtotime($request['dob']));
        $user->system_language = 1; // default value for english
        $user->status = 2; // value for pending user
        $user->save();
        //save in user table  end


        //save in selected user role table
        if ($user->iduser_role == 6) {

            $agent = new Agent();
            $agent->idUser = $user->idUser;
            $agent->iddistrict = $district;
            $agent->idelection_division = $request['electionDivision'];
            $agent->idpolling_booth = $request['pollingBooth'];
            $agent->idgramasewa_division = $request['gramasewaDivision'];
            $agent->idvillage = $request['village'];
            $agent->idethnicity = $request['ethnicity'];
            $agent->idreligion = $request['religion'];
            $agent->ideducational_qualification = $request['educationalQualification'];
            $agent->idnature_of_income = $request['natureOfIncome'];
            $agent->idcareer = $request['career'];
            $agent->referral_code = $this->generateReferral($user->idUser);
            $agent->is_government = $request['isGovernment'];
            $agent->status = 3;// value for pending user
            $agent->save();

        } else if ($user->iduser_role == 7) {

            $member = new Member();
            $member->idUser = $user->idUser;
            $member->iddistrict = $district;
            $member->idelection_division = $agent->idelection_division;
            $member->idpolling_booth = $agent->idpolling_booth;
            $member->idgramasewa_division = $agent->idgramasewa_division;
            $member->idvillage = $agent->idvillage;
            $member->idethnicity = $request['ethnicity'];
            $member->idreligion = $request['religion'];
            $member->ideducational_qualification = $request['educationalQualification'];
            $member->idnature_of_income = $request['natureOfIncome'];
            $member->idcareer = $request['career'];
            $member->is_government = $request['isGovernment'];
            $member->status = 3;// value for pending user
            $member->save();

        }
//        save in selected user role table end

        return response()->json(['success' => 'User Registered Successfully!']);

    }


    /**
     *update user details
     */
    public function update(Request $request)
    {

        //validation start
        $validationMessages = [
            'nic.required' => 'NIC No should be provided!',
            'nic.max' => 'NIC No invalid!',
            'title.required' => 'User title should be provided!',
            'title.numeric' => 'User title invalid!',
            'firstName.required' => 'First name should be provided!',
            'firstName.regex' => 'First name can only contain characters!',
            'firstName.max' => 'First name must be less than 50 characters!',
            'lastName.required' => 'Last name should be provided!',
            'lastName.regex' => 'Last name can only contain characters!',
            'lastName.max' => 'Last name must be less than 50 characters!',
            'username.required' => 'Username should be provided!',
            'username.max' => 'Username must be less than 50 characters!',
            'email.email' => 'Email format invalid!',
            'email.max' => 'Email must be less than 255 characters!',
            'password.required' => 'Password should be provided!',
            'password.confirmed' => 'Passwords didn\'t match!',
            'phone.numeric' => 'Phone number can only contain numbers!',
            'userRole.required' => 'User role should be provided!',
            'userRole.numeric' => 'User role should be provided!',
            'dob.required' => 'Date of birth should be provided!',
            'dob.date' => 'Date of birth format invalid!',
            'dob.before' => 'Date of birth should be a valid birthday!',
            'gender.required' => 'Gender should be provided!',
            'gender.boolean' => 'Gender invalid!',

        ];

        $validator = \Validator::make($request->all(), [
            'userRole' => 'required|numeric',
            'title' => 'required|numeric',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required|boolean',
            'username' => 'required|max:50',
            'password' => 'nullable|confirmed',


        ], $validationMessages);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if ($request['userRole'] == 2 || $request['userRole'] == 3 || $request['userRole'] == 5 || $request['userRole'] == 6 || $request['userRole'] == 7) {

            $validator = \Validator::make($request->all(), [
                'nic' => 'required|max:15',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|numeric',
                'dob' => 'required|date|before:today',

            ], $validationMessages);


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            if (User::where('nic', $request['nic'])->where('idUser', '!=', $request['userId'])->first() != null) {
                return response()->json(['errors' => ['nic' => 'NIC No already exist!']]);
            }
        }

        if (Auth::user()->iduser_role == 2) {
            if ($request['office'] != null) {
                $office = $request['office'];
            } else {
                return response()->json(['errors' => ['office' => 'Office should be provided!']]);
            }
        } else {
            $office = Auth::user()->idoffice;
        }

        if (User::where('username', $request['username'])->where('idUser', '!=', $request['userId'])->first() != null) {
            return response()->json(['errors' => ['username' => 'Username already exist!']]);
        }


        if ($request['password'] != null) {
            if (User::find(intval($request['userId']))->password != Hash::make($request['oldPassword'])) {
                return response()->json(['errors' => ['oldPassword' => 'Old password incorrect!']]);
            }

        }

        if ($request['userRole'] == 3) {
            $exist = User::where('idoffice', $office)->where('iduser_role', 3)->where('idUser', '!=', $request['userId'])->first();
            if ($exist != null) {
                return response()->json(['errors' => ['error' => 'Office admin has been already created!']]);
            }
        }

        if (isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender']) {

            return response()->json(['errors' => ['title' => 'Please re-check title and gender!']]);

        }

        //validation end


        //update in user table
        $user = User::find(intval($request['userId']));
        $user->idoffice = $office;
        $user->iduser_role = $request['userRole'];
        $user->iduser_title = $request['title'];
        $user->fName = $request['firstName'];
        $user->lName = $request['lastName'];
        $user->nic = $request['nic'];
        $user->gender = $request['gender'];
        $user->contact_no1 = $request['phone'];
        $user->contact_no2 = null;
        $user->address = $request['address'];
        if ($request['password'] != null) {
            $user->password = Hash::make($request['password']);
        }
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->bday = date('Y-m-d', strtotime($request['dob']));
        $user->save();
        //update in user table  end

        //update in selected user role details

        //update in selected user role details end

        return response()->json(['success' => 'User Registered Successfully!']);

    }

    public function generateReferral($uid)
    {

        $user = User::find(intval($uid));
        $name = $user->fName;
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $referral = substr(str_shuffle($permitted_chars), 0, 2) . $name[0] . substr(str_shuffle($permitted_chars), 0, 2) . substr(str_shuffle($user->office->office_name), 0, 2);
//        $referral [7] = 2 randoms from row . first name first character . 2 randoms from row . 2 randoms from office name;

        if ($user->iduser_role == 3) {
            $exist = OfficeAdmin::where('referral_code', $referral)->first();
            if ($exist != null) {
                $this->generateReferral($uid);
            } else {
                return $referral;
            }
        } else if ($user->iduser_role == 6) {
            $exist = Agent::where('referral_code', $referral)->first();
            if ($exist != null) {
                $this->generateReferral($uid);
            } else {
                return $referral;
            }
        } else {
            return $referral;
        }
    }

    public function getById(Request $request)
    {

        $user = User::with(['officeAdmin', 'userRole', 'userTitle', 'agent.electionDivision', 'agent.pollingBooth', 'agent.gramasewaDivision', 'agent.village'])->where('idUser', intval($request['id']))->where('idoffice', Auth::user()->idoffice)->first();
        if ($user != null) {
            return response()->json(['success' => $user]);
        } else {
            return response()->json(['errors' => ['error' => 'The user you are trying to view is invalid!']]);
        }
    }

    public function getOfficeAdminByReferral(Request $request)
    {
        $referral = $request['referral_code'];
        $officeAdmin = OfficeAdmin::where('referral_code', $referral)->where('status', 1)->first();
        $titles = UserTitle::where('status', 1)->select(['iduser_title', 'name_en','name_si','name_ta', 'gender'])->get();
        $careers = Career::where('status',1)->select(['idcareer',  'name_en','name_si','name_ta'])->get();
        $ethnicities = Ethnicity::where('status',1)->select(['idethnicity','name_en','name_si','name_ta'])->get();
        $religions = Religion::where('status',1)->select(['idreligion', 'name_en','name_si','name_ta'])->get();
        $educationQualifications = EducationalQualification::where('status',1)->select(['ideducational_qualification', 'name_en','name_si','name_ta'])->get();
        $natureOfIncomes = NatureOfIncome::where('status',1)->select(['idnature_of_income', 'name_en','name_si','name_ta'])->get();
        $electionDivisions = ElectionDivision::where('status',1)->select(['idelection_division', 'name_en','name_si','name_ta'])->get();

        if ($officeAdmin != null) {
            return response()->json(['sucess' =>
                ['referral_code' => $referral,
                 'titles' => $titles,
                    'careers'=>$careers,
                    'ethnicities'=>$ethnicities,
                    'religions'=>$religions,
                    'educationQualifications'=>$educationQualifications,
                    'natureOfIncomes'=>$natureOfIncomes,
                    'electionDivisions'=>$electionDivisions
                ]], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['errors' => ['referral_code' => 'Office admin referral code invalid!']]);
        }
    }

}
