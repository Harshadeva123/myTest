<?php

namespace App\Http\Controllers\Api;

use App\Agent;
use App\Career;
use App\ElectionDivision;
use App\GramasewaDivision;
use App\Http\Controllers\TaskController;
use App\Member;
use App\MemberAgent;
use App\Office;
use App\OfficeAdmin;
use App\PollingBooth;
use App\Task;
use App\TaskAge;
use App\TaskCareer;
use App\TaskEducation;
use App\TaskEthnicity;
use App\TaskIncome;
use App\TaskReligion;
use App\User;
use App\UserTitle;
use App\Village;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiRegistrationController extends Controller
{
    public function getByJobSector(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required'
        ], [
            'id.required' => 'Please provide selected job sector!'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        $fallBack = 'name_en';
        $apiLang = $request['lang'];

        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }

        $careers = Career::where('status', 1)->where('sector', $request['id'])->select(['idcareer', $lang, 'name_en'])->get();
        foreach ($careers as $item) {
            $item['label'] = $item[$lang] != null ? $item[$lang] : $item[$fallBack];
            $item['id'] = $item['idcareer'];
            unset($item->name_en);
            unset($item->$lang);
            unset($item->idcareer);
        }

        return response()->json(['success' => $careers, 'statusCode' => 0]);

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
                return response()->json(['error' => 'Office referral code should be provided!', 'statusCode' => -99]);
            }
            $officeAdmin = OfficeAdmin::where('referral_code', $request['referral_code'])->whereIn('status', [1, 2])->first();
            if ($officeAdmin == null) {
                return response()->json(['error' => 'Referral code invalid!', 'statusCode' => -99]);
            }
            $office = User::find($officeAdmin->idUser)->idoffice;
            $district = Office::find(intval($office))->iddistrict;

            if ($request['electionDivision'] == null) {
                return response()->json(['error' => 'Election division should be provided!', 'statusCode' => -99]);
            } else if (ElectionDivision::where('idelection_division', $request['electionDivision'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => ['electionDivision' => 'Election division invalid!']]);
            }
            if ($request['pollingBooth'] == null) {
                return response()->json(['error' => 'Polling booth should be provided!', 'statusCode' => -99]);
            } else if (PollingBooth::where('idpolling_booth', $request['pollingBooth'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => 'Polling booth invalid!', 'statusCode' => -99]);
            }
            if ($request['gramasewaDivision'] == null) {
                return response()->json(['error' => 'Gramasewa division should be provided!', 'statusCode' => -99]);
            } else if (GramasewaDivision::where('idgramasewa_division', $request['gramasewaDivision'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => 'Gramasewa division invalid!', 'statusCode' => -99]);
            }
            if ($request['village'] == null) {
                return response()->json(['error' => 'Village should be provided!', 'statusCode' => -99]);
            } else if (Village::where('idvillage', $request['village'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => 'Village invalid!', 'statusCode' => -99]);
            }
        } else if ($request['userRole'] == 7) {
            if ($request['referral_code'] == null) {
                return response()->json(['error' => 'Agent referral code should be provided!', 'statusCode' => -99]);
            }
            $agent = Agent::where('referral_code', $request['referral_code'])->whereIn('status', [1, 2])->first();
            if ($agent == null) {
                return response()->json(['error' => 'Referral code invalid!', 'statusCode' => -99]);
            }

            $office = User::find(intval($agent->idUser))->idoffice;
            $district = Office::find(intval($office))->iddistrict;
        } else {
            return response()->json(['error' => 'User role unknown!', 'statusCode' => -99]);
        }

        if(isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender'] && $request['gender'] != 3){

            return response()->json(['errors' => ['title'=>'Please re-check title and gender!']]);

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
            $agent->isSms = 0;// non sms user
            $agent->status = 2;// value for pending user
            $agent->save();

            $defaultTask = Task::where('idoffice', $user->idoffice)->where('status', 3)->where('isDefault', 1)->first();
            if ($defaultTask != null) {
                $task = new Task();
                $task->idUser = $user->idUser;
                $task->idoffice = $user->idoffice;
                $task->assigned_by = $defaultTask->assigned_by;
                $task->task_no = 1;
                $task->target = $defaultTask->target;
                $task->task_gender = $defaultTask->task_gender;
                $task->task_job_sector = $defaultTask->task_job_sector;
                $task->completed_amount = $defaultTask->completed_amount;
                $task->description = $defaultTask->description;
                $task->isDefault = 0;
                $task->status = 2;//pending
                $task->save();

                if ($defaultTask->religions != null) {
                    foreach ($defaultTask->religions as $religion) {
                        $new = new TaskReligion();
                        $new->idtask = $task->idtask;
                        $new->idreligion = $religion->idreligion;
                        $new->status = 1;
                        $new->save();
                    }
                }

                if ($defaultTask->ethnicities != null) {
                    foreach ($defaultTask->ethnicities as $ethnicities) {
                        $new = new TaskEthnicity();
                        $new->idtask = $task->idtask;
                        $new->idethnicity = $ethnicities->idethnicity;
                        $new->status = 1;
                        $new->save();
                    }
                }

                if ($defaultTask->careers != null) {
                    foreach ($defaultTask->careers as $careers) {
                        $new = new TaskCareer();
                        $new->idtask = $task->idtask;
                        $new->idcareer = $careers->idcareer;
                        $new->status = 1;
                        $new->save();
                    }
                }

                if ($defaultTask->incomes != null) {
                    foreach ($defaultTask->incomes as $incomes) {
                        $new = new TaskIncome();
                        $new->idtask = $task->idtask;
                        $new->idnature_of_income = $incomes->idnature_of_income;
                        $new->status = 1;
                        $new->save();
                    }
                }

                if ($defaultTask->educations != null) {
                    foreach ($defaultTask->educations as $educations) {
                        $new = new TaskEducation();
                        $new->idtask = $task->idtask;
                        $new->ideducational_qualification = $educations->ideducational_qualification;
                        $new->status = 1;
                        $new->save();
                    }
                }

                if ($defaultTask->age != null) {
                    $new = new TaskAge();
                    $new->idtask = $task->idtask;
                    $new->comparison = $defaultTask->age->comparison;
                    $new->minAge = $defaultTask->age->minAge;
                    $new->maxAge = $defaultTask->age->maxAge;
                    $new->status = 1;
                    $new->save();
                }

            }

        } else if ($user->iduser_role == 7) {
            $referralAgent = Agent::where('referral_code', $request['referral_code'])->first();

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
            $member->isSms = 0;// non sms user
            $member->status = 1;// always 1 for member . only change member_agent table status
            $member->save();

            $memberAgent = new MemberAgent();
            $memberAgent->idmember = $member->idmember;
            $memberAgent->idagent = $referralAgent->idagent;
            $memberAgent->idoffice = User::find($referralAgent->idUser)->idoffice;
            $memberAgent->status = 2; //pending member
            $memberAgent->save();

//            app(TaskController::class)->updateTask($member->idUser, $referralAgent->idUser);

        }
//        save in selected user role table end

//        $token = $user->createToken('authToken')->accessToken; //generate access token

        return response()->json(['success' => 'User registered successfully!', 'statusCode' => 0]);
    }

    public function generateReferral($uid)
    {
        $user = User::find(intval($uid));
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $referral = substr(str_shuffle($permitted_chars), 0, 7);
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

    public function storeSmsUser(Request $request)
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

        if (Auth::user()->iduser_role != 6) {
            return response()->json(['error' => 'You are not an agent!', 'statusCode' => -99]);
        }

        if ($request['userRole'] == 7) {

            $agent = Agent::where('idUser', Auth::user()->idUser)->where('status', 1)->first();
            if ($agent == null) {
                return response()->json(['error' => 'Referral code invalid!', 'statusCode' => -99]);
            }

            $office = User::find(intval($agent->idUser))->idoffice;
            $district = Office::find(intval($office))->iddistrict;
        } else {
            return response()->json(['error' => 'User role unknown!', 'statusCode' => -99]);
        }

        if(isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender'] && $request['gender'] != 3){

            return response()->json(['errors' => ['title'=>'Please re-check title and gender!']]);

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
        $member->current_agent = $agent->idagent;
        $member->is_government = $request['isGovernment'];
        $member->isSms = 1;// sms user
        $member->status = 1;// always 1 for member . only change member_agent table status
        $member->save();

        $memberAgent = new MemberAgent();
        $memberAgent->idmember = $member->idmember;
        $memberAgent->idagent = $agent->idagent;
        $memberAgent->idoffice = User::find($agent->idUser)->idoffice;
        $memberAgent->status = 1; //pending member
        $memberAgent->save();

//        app(TaskController::class)->updateTask($member->idUser, $agent->idUser);

//        save in selected user role table end

//        $token = $user->createToken('authToken')->accessToken; //generate access token

        return response()->json(['success' => 'User registered successfully!', 'statusCode' => 0]);
    }
}
