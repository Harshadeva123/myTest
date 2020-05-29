<?php

namespace App\Http\Controllers\Api;

use App\Agent;
use App\Career;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
use App\Member;
use App\MemberAgent;
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
use App\Http\Controllers\Controller;

class ApiUserController extends Controller
{
    public function login(Request $request)
    {
        //validation start
        $validator = \Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'lang'=>'required'
        ], [
            'username.required' => 'Username should be provided!',
            'username.string' => 'Username must be a string!',
            'password.required' => 'Password should be provided!',
            'password.string' => 'Password should be a string!',
            'lang.required' => 'Please provide user language!'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }

        if (!Auth::attempt(['username'=>$request->username,'password'=>$request->password])) {
            return response()->json(['error' => 'Username or Password Incorrect!','statusCode'=>-99]);
        }

        $token = Auth::user()->createToken('authToken')->accessToken; //generate access token

        return response()->json(['success' => ['userRole' => Auth::user()->iduser_role,'accessToken' => $token], 'statusCode' => 0]);

    }

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
            'isGovernment' => 'required',
            'gender' => 'required|numeric',
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
            'isGovernment.required' => 'Job sector should be provided!',
            'password.required' => 'Password should be provided!',
            'password.confirmed' => 'Passwords didn\'t match!',
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
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        if ($request['userRole'] == 6) {
            if ($request['referral_code'] == null) {
                return response()->json(['error' => 'Office referral code should be provided!','statusCode'=>-99]);
            }
            $officeAdmin = OfficeAdmin::where('referral_code', $request['referral_code'])->whereIn('status', [1, 2])->first();
            if ($officeAdmin == null) {
                return response()->json(['error' => 'Referral code invalid!','statusCode'=>-99]);
            }
            $office = $officeAdmin->user->idoffice;
            $district = Office::find(intval($office))->iddistrict;

            if ($request['electionDivision'] == null) {
                return response()->json(['error' => 'Election division should be provided!','statusCode'=>-99]);
            } else if (ElectionDivision::where('idelection_division', $request['electionDivision'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => ['electionDivision' => 'Election division invalid!']]);
            }
            if ($request['pollingBooth'] == null) {
                return response()->json(['error' =>  'Polling booth should be provided!','statusCode'=>-99]);
            } else if (PollingBooth::where('idpolling_booth', $request['pollingBooth'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' =>  'Polling booth invalid!','statusCode'=>-99]);
            }
            if ($request['gramasewaDivision'] == null) {
                return response()->json(['error'=> 'Gramasewa division should be provided!','statusCode'=>-99]);
            } else if (GramasewaDivision::where('idgramasewa_division', $request['gramasewaDivision'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => 'Gramasewa division invalid!','statusCode'=>-99]);
            }
            if ($request['village'] == null) {
                return response()->json(['error'  => 'Village should be provided!','statusCode'=>-99]);
            } else if (Village::where('idvillage', $request['village'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => 'Village invalid!','statusCode'=>-99]);
            }
        } else if ($request['userRole'] == 7) {
            if ($request['referral_code'] == null) {
                return response()->json(['error' => 'Agent referral code should be provided!','statusCode'=>-99]);
            }
            $agent = Agent::where('referral_code', $request['referral_code'])->whereIn('status', [1, 2])->first();
            if ($agent == null) {
                return response()->json(['error' =>  'Referral code invalid!','statusCode'=>-99]);
            }

            $office = User::find(intval($agent->idUser))->idoffice;
            $district = Office::find(intval($office))->iddistrict;
        } else {
            return response()->json(['error' =>  'User role unknown!','statusCode'=>-99]);
        }

        if (isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender']) {
            return response()->json(['error' => 'Please re-check title and gender!','statusCode'=>-99]);
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
        $user->system_language = $request['lang']; // default value for english
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
            $agent->status = 2;// value for pending user
            $agent->save();

        } else if ($user->iduser_role == 7) {

            $referralAgent = Agent::where('referral_code',$request['referral_code'])->first();

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
            $member->current_agent = $referralAgent->idagent;
            $member->is_government = $request['isGovernment'];
            $member->status = 1;// always 1 for member . only change member_agent table status
            $member->save();

            $memberAgent = new MemberAgent();
            $memberAgent->idmember = $member->idmember;
            $memberAgent->idagent = $referralAgent->idagent;
            $memberAgent->idoffice = User::find($referralAgent->idUser)->idoffice;
            $memberAgent->status   = 2; //pending member
            $memberAgent->save();

        }
//        save in selected user role table end

//        $token = $user->createToken('authToken')->accessToken; //generate access token

        return response()->json(['success' =>'User registered successfully!','statusCode'=>0]);
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
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }
        if ($request['userRole'] == 2 || $request['userRole'] == 3 || $request['userRole'] == 5 || $request['userRole'] == 6 || $request['userRole'] == 7) {

            $validator = \Validator::make($request->all(), [
                'nic' => 'required|max:15',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|numeric',
                'dob' => 'required|date|before:today',

            ], $validationMessages);


            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
            }

            if (User::where('nic', $request['nic'])->where('idUser', '!=', $request['userId'])->first() != null) {
                return response()->json(['error' => 'NIC No already exist!','statusCode'=>-99]);
            }
        }

        if (Auth::user()->iduser_role == 2) {
            if ($request['office'] != null) {
                $office = $request['office'];
            } else {
                return response()->json(['error' =>'Office should be provided!','statusCode'=>-99]);
            }
        } else {
            $office = Auth::user()->idoffice;
        }

        if (User::where('username', $request['username'])->where('idUser', '!=', $request['userId'])->first() != null) {
            return response()->json(['error' =>'Username already exist!','statusCode'=>-99]);
        }


        if ($request['password'] != null) {
            if (User::find(intval($request['userId']))->password != Hash::make($request['oldPassword'])) {
                return response()->json(['error' =>  'Old password incorrect!','statusCode'=>-99]);
            }

        }

        if ($request['userRole'] == 3) {
            $exist = User::where('idoffice', $office)->where('iduser_role', 3)->where('idUser', '!=', $request['userId'])->first();
            if ($exist != null) {
                return response()->json(['error' => 'Office admin has been already created!','statusCode'=>-99]);
            }
        }

        if (isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender']) {

            return response()->json(['error' =>'Please re-check title and gender!','statusCode'=>-99]);

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

        return response()->json(['success' => 'User Registered Successfully!','statusCode'=>0]);

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
            return response()->json(['success' => $user,'statusCode'=>0]);
        } else {
            return response()->json(['error' => 'The user you are trying to view is invalid!','statusCode'=>-99]);
        }
    }

    public function getOfficeAdminByReferral(Request $request)
    {
        $apiLang = $request['lang'];
        $fallBack = 'name_en';
        $referral = $request['referral_code'];

        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }
        $titles = UserTitle::where('status', 1)->select(['iduser_title', $lang, 'name_en', 'gender'])->get();
        $titles = $this->filterLanguage($titles, $lang, $fallBack,'iduser_title');


        $careers = Career::where('status', 1)->select(['idcareer', $lang, 'name_en'])->get();
        $careers = $this->filterLanguage($careers, $lang, $fallBack,'idcareer');

        $ethnicities = Ethnicity::where('status', 1)->select(['idethnicity', $lang, 'name_en'])->get();
        $ethnicities = $this->filterLanguage($ethnicities, $lang, $fallBack,'idethnicity');

        $religions = Religion::where('status', 1)->select(['idreligion', $lang, 'name_en'])->get();
        $religions = $this->filterLanguage($religions, $lang, $fallBack,'idreligion');

        $educationQualifications = EducationalQualification::where('status', 1)->select(['ideducational_qualification', $lang, 'name_en'])->get();
        $educationQualifications = $this->filterLanguage($educationQualifications, $lang, $fallBack,'ideducational_qualification');

        $natureOfIncomes = NatureOfIncome::where('status', 1)->select(['idnature_of_income', $lang, 'name_en'])->get();
        $natureOfIncomes = $this->filterLanguage($natureOfIncomes, $lang, $fallBack,'idnature_of_income');

        $electionDivisions = ElectionDivision::where('status', 1)->select(['idelection_division', $lang, 'name_en'])->get();
        $electionDivisions = $this->filterLanguage($electionDivisions, $lang, $fallBack,'idelection_division');

        $officeAdmin = OfficeAdmin::where('referral_code', $referral)->where('status', 1)->first();

        if ($officeAdmin != null) {
            return response()->json(['success' =>
                ['referral_code' => $referral,
                    'titles' => $titles,
                    'careers' => $careers,
                    'ethnicities' => $ethnicities,
                    'religions' => $religions,
                    'educationQualifications' => $educationQualifications,
                    'natureOfIncomes' => $natureOfIncomes,
                    'electionDivisions' => $electionDivisions
                ],'statusCode'=>0], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['error' => 'Office admin referral code invalid!','statusCode'=>-99]);
        }
    }

    public function getAgentByReferral(Request $request)
    {
        $apiLang = $request['lang'];
        $fallBack = 'name_en';
        $referral = $request['referral_code'];

        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }

        $agent = Agent::where('referral_code', $referral)->where('status', 1)->first();

        $titles = UserTitle::where('status', 1)->select(['iduser_title', 'name_en', $lang, 'gender'])->get();
        $titles = $this->filterLanguage($titles, $lang, $fallBack,'iduser_title');

        $careers = Career::where('status', 1)->select(['idcareer', 'name_en', $lang])->get();
        $careers = $this->filterLanguage($careers, $lang, $fallBack,'idcareer');

        $ethnicities = Ethnicity::where('status', 1)->select(['idethnicity', 'name_en', $lang])->get();
        $ethnicities = $this->filterLanguage($ethnicities, $lang, $fallBack,'idethnicity');

        $religions = Religion::where('status', 1)->select(['idreligion', 'name_en', $lang])->get();
        $religions = $this->filterLanguage($religions, $lang, $fallBack,'idreligion');

        $educationQualifications = EducationalQualification::where('status', 1)->select(['ideducational_qualification', 'name_en', $lang])->get();
        $educationQualifications = $this->filterLanguage($educationQualifications, $lang, $fallBack,'ideducational_qualification');

        $natureOfIncomes = NatureOfIncome::where('status', 1)->select(['idnature_of_income', 'name_en', $lang])->get();
        $natureOfIncomes = $this->filterLanguage($natureOfIncomes, $lang, $fallBack,'idnature_of_income');

        if ($agent != null) {
            return response()->json(['success' =>
                ['referral_code' => $referral,
                    'titles' => $titles,
                    'careers' => $careers,
                    'ethnicities' => $ethnicities,
                    'religions' => $religions,
                    'educationQualifications' => $educationQualifications,
                    'natureOfIncomes' => $natureOfIncomes,
                ],'statusCode'=>0], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['error' => 'Agent referral code invalid!','statusCode'=>-99]);
        }
    }

    public function filterLanguage($collection, $lang, $fallBack,$id)
    {
        foreach ($collection as $item) {
            $item['label'] = $item[$lang] != null ? $item[$lang] : $item[$fallBack];
            $item['id'] = $item[$id];
            unset($item->name_en);
            unset($item->$lang);
            unset($item->$id);
        }
        return $collection;
    }

    public function getAgents(Request $request){
        if(Auth::user()->iduser_role != 7){
            return response()->json(['error' => 'You are not a member','statusCode'=>-99]);
        }
        $memberAgents = MemberAgent::where('idmember',Auth::user()->member->idmember)->get();
        foreach ($memberAgents as $memberAgent) {
            $memberAgent['id'] = Agent::find($memberAgent->idagent)->idUser;
            $memberAgent['name'] = User::find(Agent::find($memberAgent->idagent)->idUser)->fName.' '.User::find(Agent::find($memberAgent->idagent)->idUser)->lName;
            $memberAgent['office'] = User::find(Agent::find($memberAgent->idagent)->idUser)->office->office_name;
            $memberAgent['availability'] = $memberAgent->status;
            unset($memberAgent->idmember_agent);
            unset($memberAgent->idmember);
            unset($memberAgent->idagent);
            unset($memberAgent->idoffice);
            unset($memberAgent->status);
            unset($memberAgent->created_at);
            unset($memberAgent->updated_at);

        }
        return response()->json(['success' => $memberAgents,'statusCode'=>0]);

    }

    public function getPendingMembers(){
        if(Auth::user()->iduser_role != 6){
            return response()->json(['error' => 'You are not an agent','statusCode'=>-99]);
        }
        $memberAgents = MemberAgent::where('idagent',Auth::user()->agent->idagent)->where('status',2)->get();
        foreach ($memberAgents as $memberAgent) {
            $memberAgent['id'] = $memberAgent->idmember_agent;
            $memberAgent['name'] = User::find(Member::find($memberAgent->idmember)->idUser)->fName.' '.User::find(Member::find($memberAgent->idmember)->idUser)->lName;
            $memberAgent['requested'] = $memberAgent->created_at->format('Y-m-d');
            unset($memberAgent->idmember_agent);
            unset($memberAgent->idmember);
            unset($memberAgent->idagent);
            unset($memberAgent->idoffice);
            unset($memberAgent->status);
            unset($memberAgent->updated_at);
            unset($memberAgent->created_at);

        }
        return response()->json(['success' => $memberAgents,'statusCode'=>0]);

    }

    public function getApprovedMembers(){
        if(Auth::user()->iduser_role != 6){
            return response()->json(['error' => 'You are not an agent','statusCode'=>-99]);
        }
        $memberAgents = MemberAgent::where('idagent',Auth::user()->agent->idagent)->where('status',1)->get();
        foreach ($memberAgents as $memberAgent) {
            $memberAgent['id'] = $memberAgent->idmember_agent;
            $memberAgent['name'] = User::find(Member::find($memberAgent->idmember)->idUser)->fName.' '.User::find(Member::find($memberAgent->idmember)->idUser)->lName;
            $memberAgent['requested'] = $memberAgent->created_at->format('Y-m-d');
            unset($memberAgent->idmember_agent);
            unset($memberAgent->idmember);
            unset($memberAgent->idagent);
            unset($memberAgent->idoffice);
            unset($memberAgent->status);
            unset($memberAgent->updated_at);
            unset($memberAgent->created_at);

        }
        return response()->json(['success' => $memberAgents,'statusCode'=>0]);

    }

    public function approveMember(Request $request){
        $validationMessages = [
            'id.required' => 'Record id required!',
            'id.numeric' => 'Record id invalid!',
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required|numeric',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }

        $memberAgent = MemberAgent::where('idmember_agent',$request['id'])->where('idagent',Auth::user()->agent->idagent)->first();
        if($memberAgent == null){
            return response()->json(['error' => 'Record invalid','statusCode'=>-99]);
        }

        $memberAgent->status = 1;
        $memberAgent->save();

        return response()->json(['success' => 'Member Approved!','statusCode'=>0]);

    }
}
