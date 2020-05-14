<?php

namespace App\Http\Controllers;

use App\Agent;
use App\District;
use App\ElectionDivision;
use App\Office;
use App\OfficeAdmin;
use App\PollingBooth;
use App\User;
use App\UserRole;
use App\UserTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     *render 'add user' interface
     */
    public function index()
    {
        $userRoles = UserRole::where('status', '1')->where('allow_to_manage_by',Auth::user()->iduser_role)->get();
        $userTitles = UserTitle::where('status', '1')->get();
        if(Auth::user()->iduser_role == 2) {
            $offices = Office::where('status',1)->get();
        }
        else{
            $offices = null;
        }

        return view('user.add_user', ['title' =>  __('add_user_page_title'), 'userRoles' => $userRoles,'userTitles'=>$userTitles,'offices'=>$offices]);
    }


    /**
     *add new user to database
     */
    public function store(Request $request){

        //validation start
        $validationMessages  = [
            'nic.required' => 'NIC No should be provided!',
            'nic.max' => 'NIC No invalid!',
            'nic.unique' => 'NIC No already exist!',
            'title.required' => 'User title should be provided!',
            'title.numeric' => 'User title invalid!',
            'firstName.required' => 'First name should be provided!',
            'firstName.regex' => 'First name can only contain characters!',
            'firstName.max' =>'First name must be less than 50 characters!',
            'lastName.required' => 'Last name should be provided!',
            'lastName.regex' => 'Last name can only contain characters!',
            'lastName.max' =>'Last name must be less than 50 characters!',
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
            'gender.boolean' => 'Gender invalid!'
        ];

        $validator = \Validator::make($request->all(), [
            'userRole' => 'required|exists:user_role,iduser_role',
            'username' => 'required|max:50|unique:usermaster',
            'password' => 'required|confirmed',
            'title' => 'required|numeric',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required|boolean',

        ],$validationMessages );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if($request['userRole'] == 2 || $request['userRole'] == 3 || $request['userRole'] == 5 || $request['userRole'] == 6 || $request['userRole'] == 7) {
            $validationRules = [
                'nic' => 'required|max:15|unique:usermaster',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|numeric',
                'dob' => 'required|date|before:today',
            ];

            $validator = \Validator::make($request->all(), $validationRules, $validationMessages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
        }

        if(Auth::user()->iduser_role == 2){
            if($request['office'] != null){
                $office = $request['office'];
            }
            else{
                return response()->json(['errors' => ['office'=>'Office should be provided!']]);
            }
        }
        else{
            $office = Auth::user()->idoffice;
        }

        if($request['userRole'] == 3){
            $exist = User::where('idoffice',$office)->where('iduser_role',3)->first();
            if($exist != null){
                return response()->json(['errors' => ['error'=>'Office admin has been already created!']]);
            }
        }

        if(isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender'] ){

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
        $user->bday =  date('Y-m-d', strtotime($request['dob']));
        $user->system_language = 1; // default value for english
        $user->status = 1; // default value for active user
        $user->save();
        //save in user table  end


        //save in selected user role table
        if($user->iduser_role == 3){

            $officeAdmin = new OfficeAdmin();
            $officeAdmin->idUser = $user->idUser;
            $officeAdmin->referral_code = $this->generateReferral($user->idUser);
            $officeAdmin->status = 1;
            $officeAdmin->save();

        }
        //save in selected user role table end

        return response()->json(['success' => 'User Registered Successfully!']);

    }

    /**
     *view all saved user
     */
    public function view(Request $request)
    {
        $userRole = $request['userRole'];
        $searchCol = $request['searchCol'];
        $searchText = $request['searchText'];
        $gender = $request['gender'];
        $endDate = $request['end'];
        $startDate = $request['start'];


        $query = User::query();
        if(Auth::user()->iduser_role <= 2 && !empty( $request['office'])){
            $query = $query->where('idoffice',  $request['office']);
        }
        if (!empty($userRole)) {
            $query = $query->where('iduser_role', $userRole);
        }
        if ($gender != null) {
            $query = $query->where('gender', $gender);
        }
        if (!empty($searchText)) {
            if($searchCol == 1){
                $query = $query->where('fName',  'like', '%' . $searchText . '%');

            }
            else if($searchCol == 2){
                $query = $query->where('lName',  'like', '%' . $searchText . '%');

            }
            else if($searchCol == 3){
                $query = $query->where('nic', $searchText);

            }
            else if($searchCol == 4){
                $query = $query->where('username', $searchText);

            }

        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end']));

            $query = $query->whereBetween('bday', [$startDate, $endDate]);
        }

        if(Auth::user()->iduser_role <= 2){
            $users = $query->where('status', 1)->latest()->paginate(10);
            $offices = Office::where('status',1)->get();
        }
        else{
            $users = $query->where('status', 1)->where('idoffice', intval(Auth::user()->idoffice))->latest()->paginate(10);
            $offices = null;
        }
        $userTitles = UserTitle::where('status', '1')->get();
        $userRoles = UserRole::where('status', '1')->get();
//        if(Auth::user()->iduser_role <= 2){
//            $district = District::with(['electionDivisions.pollingBooths.gramasewaDivisions.villages'])->find(1);
//
//        }
//        else{
//            $district = District::with(['electionDivisions.pollingBooths.gramasewaDivisions.villages'])->find(Auth::user()->office->iddistrict);
//
//        }
//        $someArray = json_decode($district, true);
        return view('user.view_users', ['title' =>  __('View User'),'userTitles'=>$userTitles, 'users' => $users, 'userRoles' => $userRoles,'offices'=>$offices]);
    }


    /**
     *redirect user to 'user edit' page with selected user details
     */
    public function edit(Request $request)
    {
        if((!isset($request['updateUserId'])) || $request['updateUserId'] == null){
            return redirect()->back();
        }
        $user = User::find(intval($request['updateUserId']));
        if($user == null){
            return redirect()->back();
        }
        if($user->userRole->allow_to_manage_by != Auth::user()->iduser_role){
            return redirect()->back();
        }

        $userRoles = UserRole::where('status', '1')->where('allow_to_manage_by',Auth::user()->iduser_role)->get();
        $userTitles = UserTitle::where('status', '1')->get();
        if(Auth::user()->iduser_role == 2) {
            $offices = Office::where('status', 1)->get();
        }
        else{
            $offices = null;
        }

        return view('user.edit_user', ['title' =>  __('Edit User'),'user'=>$user, 'userRoles' => $userRoles,'userTitles'=>$userTitles,'offices'=>$offices]);

    }

    /**
     *update user details
     */
    public function update(Request $request){

        //validation start
        $validationMessages = [
            'nic.required' => 'NIC No should be provided!',
            'nic.max' => 'NIC No invalid!',
            'title.required' => 'User title should be provided!',
            'title.numeric' => 'User title invalid!',
            'firstName.required' => 'First name should be provided!',
            'firstName.regex' => 'First name can only contain characters!',
            'firstName.max' =>'First name must be less than 50 characters!',
            'lastName.required' => 'Last name should be provided!',
            'lastName.regex' => 'Last name can only contain characters!',
            'lastName.max' =>'Last name must be less than 50 characters!',
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


        ],$validationMessages );


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if($request['userRole'] == 2 || $request['userRole'] == 3 || $request['userRole'] == 5 || $request['userRole'] == 6 || $request['userRole'] == 7) {

            $validator = \Validator::make($request->all(), [
                'nic' => 'required|max:15',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|numeric',
                'dob' => 'required|date|before:today',

            ], $validationMessages);


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            if(User::where('nic',$request['nic'])->where('idUser','!=',$request['userId'])->first() != null){
                return response()->json(['errors' => ['nic'=>'NIC No already exist!']]);
            }
        }

        if(Auth::user()->iduser_role == 2){
            if($request['office'] != null){
                $office = $request['office'];
            }
            else{
                return response()->json(['errors' => ['office'=>'Office should be provided!']]);
            }
        }
        else{
            $office = Auth::user()->idoffice;
        }

        if(User::where('username',$request['username'])->where('idUser','!=',$request['userId'])->first() != null){
            return response()->json(['errors' => ['username'=>'Username already exist!']]);
        }



        if($request['password'] != null){
           if(User::find(intval($request['userId']))->password !=  Hash::make($request['oldPassword'])){
               return response()->json(['errors' => ['oldPassword'=>'Old password incorrect!']]);
           }

        }

        if($request['userRole'] == 3){
            $exist = User::where('idoffice',$office)->where('iduser_role',3)->where('idUser','!=',$request['userId'])->first();
            if($exist != null){
                return response()->json(['errors' => ['error'=>'Office admin has been already created!']]);
            }
        }

        if(isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender'] ){

            return response()->json(['errors' => ['title'=>'Please re-check title and gender!']]);

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
        if($request['password'] != null){
            $user->password = Hash::make($request['password']);
        }
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->bday =  date('Y-m-d', strtotime($request['dob']));
        $user->save();
        //update in user table  end

        //update in selected user role details

        //update in selected user role details end

        return response()->json(['success' => 'User Registered Successfully!']);

    }

    public function generateReferral($uid){

        $user  =  User::find(intval($uid));
        $name = $user->fName;
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $referral =  substr(str_shuffle($permitted_chars), 0, 2).$name[0].substr(str_shuffle($permitted_chars), 0, 2).substr(str_shuffle($user->office->office_name), 0, 2);
//        $referral [7] = 2 randoms from row . first name first character . 2 randoms from row . 2 randoms from office name;

        if($user->iduser_role == 3){
            $exist = OfficeAdmin::where('referral_code',$referral)->first();
            if($exist != null){
                $this->generateReferral($uid);
            }
            else{
                return $referral;
            }
        }
        else if($user->iduser_role == 6){
            $exist = Agent::where('refferal_code',$referral)->first();
            if($exist != null){
                $this->generateReferral($uid);
            }
            else{
                return $referral;
            }
        }
        else{
            return $referral;
        }
    }


    public function viewPendingAgents(Request $request){
        $searchCol = $request['searchCol'];
        $searchText = $request['searchText'];
        $gender = $request['gender'];
        $endDate = $request['end'];
        $startDate = $request['start'];

        $query = User::query();

        if ($gender != null) {
            $query = $query->where('gender', $gender);
        }
        if (!empty($searchText)) {
            if($searchCol == 1){
                $query = $query->where('fName', 'like', '%' . $searchText . '%');

            }
            else if($searchCol == 2){
                $query = $query->where('lName',  'like', '%' . $searchText . '%');

            }
            else if($searchCol == 3){
                $query = $query->where('nic', $searchText);

            }


        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'].'+1 day'));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $users = $query->where('status', 2)->where('idoffice', intval(Auth::user()->idoffice))->where('iduser_role', 6)->latest()->paginate(10);

        return view('user.pending_requests', ['title' =>  __('Pending Requests'), 'users' => $users]);

    }

    public function getById(Request $request){
        if(Auth::user()->iduser_role <= 2){
            $user =  User::with(['office','officeAdmin','userRole','userTitle','agent.electionDivision','agent.pollingBooth','agent.gramasewaDivision','agent.village'])->find(intval($request['id']));
        }
        else{
            $user =  User::with(['officeAdmin','userRole','userTitle','agent.electionDivision','agent.pollingBooth','agent.gramasewaDivision','agent.village'])->where('idUser',intval($request['id']))->where('idoffice',Auth::user()->idoffice)->first();
        }
        if($user != null){
            return response()->json(['success' => $user]);
        }
        else{
            return response()->json(['errors' => ['error'=>'The user you are trying to view is invalid!']]);
        }
    }

    public function approveAgent(Request $request){
        $id  = $request['id'];
        $agent = User::find(intval($id));
        if($agent->idoffice == Auth::user()->idoffice){
            $agent->status = 1;
            $agent->save();

            $agent->agent->status = 1;
            $agent->agent->save();

            return response()->json(['success' => 'Approved']);

        }
        else{
            return response()->json(['errors' => ['error'=>'Agent approving process invalid!']]);
        }
    }








//
//    public function getPasswordById(Request $request)
//    {
//        $pid = $request['pid'];
//        $findUserPword = User::find(intval($pid));
//        return response()->json($findUserPword);
//    }
//
//    public function createUser(Request $request)
//    {
//
//        $title = $request->get('uTitle');
//        $fName = $request->get('fName');
//        $lName = $request->get('lName');
//        $lName = $request->get('nic');
//        $gender = $request->get('gender');
//        $contactNo = $request->get('contactNo');
//        $dob = $request->get('dob');
//        $utype = $request->get('utype');
//        $username = $request->get('username');
//        $pass2 = $request->get('pass2');
//        $agency = $request->get('agency');
//
//        $rules = [
//            'uTitle' => 'required|in:Mr.,Mrs.,Miss.',
//            'fName' => 'required|max:255',
//            'lName' => 'required|max:255',
//            'contactNo' => 'required',
//            'utype' => 'required|exists:meta_userrole,idUserRole',
//            'username' => 'required|email|unique:usermaster,username',
//            'pass2' => 'required|min:8',
//            'agency'=>'required',
//            'proImage' => 'nullable|image|max:3048',
//
//
//        ];
//
//        $customMessages = [
//            'agency,required'=>'Agency should be provided!',
//            'proImage.image'=>'User image format invalid!',
//            'proImage.max'=>'User image size should lower than 3MB!',
//            'uTitle.required' => 'Title should be provided!',
//            'uTitle.in' => 'Title Invalid!',
//            'fName.required' => 'First name should be provided!',
//            'fName.max' => 'First name is too long!',
//            'lName.required' => 'Last name should be provided!',
//            'lName.max' => 'Last name is too long!',
//            'contactNo.required' => 'Contact number should be provided!',
//            'utype.required' => 'User type should be provided!',
//            'utype.exists' => 'User type invalid!',
//            'username.email' => 'Email format invalid!',
//            'username.required' => 'Username should be provided!',
//            'username.unique' => 'Username already taken!',
//            'pass2.min' => 'The Password must be at least 8 characters.',
//
//        ];
//
//
//        $this->validate($request, $rules, $customMessages);
//        $name = "";
//        if ($request->hasfile('proImage')) {
//            $file = $request->file('proImage');
//            $name =  time().$file->getClientOriginalName();
//            $file->move(public_path('assets/images/users/'), $name);
//        }
//
//
//        $user = new User();
//        $user->title = $title;
//
//        $user->fName = strtoupper($fName);
//        $user->Lname = strtoupper($lName);
//        $user->gender = $gender;
//        $user->Company = $agency;
//        $user->bday = date('Y-m-d', strtotime($dob));
//        $user->contactNo = $contactNo;
//        $user->UserRole = intval($utype);
//        $user->username = $username;
//
//
//        $advanceEncryption = (new  \App\MyResources\AdvanceEncryption($pass2, "Nova6566", 256));
//
//        $user->password = $advanceEncryption->encrypt();
//        $user->image = $name;
//        $user->status = 1;
//        $user->save();
//
//        return redirect()->route('add_user')->with('success', 'User Information has been added');
//
//    }
//
//
//    public function updateo(Request $request)
//    {
//        $hiddenVID = $request['hiddenVID'];
//        $update_Title = $request['update_Title'];
//        $update_fName = $request['update_fName'];
//        $update_lName = $request['update_lName'];
//        $update_gender = $request['update_gender'];
//        $update_contactNo = $request['update_contactNo'];
//        $update_type = $request['update_type'];
//        $update_username = $request['update_username'];
//        $dob = $request['dob'];
//        $update_agency = $request['update_agency'];
//
//        $validator = \Validator::make($request->all(), [
//            'update_Title' => 'required|in:Mr.,Mrs.,Miss.',
//            'update_fName' => 'required|max:255',
//            'update_lName' => 'required|max:255',
//            'update_gender' => 'required',
//            'update_contactNo' => 'required',
//            'update_type' => 'required|exists:meta_userrole,idUserRole',
//            'update_username' => 'required|email',
//            'update_agency'=>'required',
//            'update_proImage' => 'nullable|image|max:3048',
//        ], [
//            'update_agency.required'=>'Agency should be provided!',
//            'update_fName.max' => 'First name is too long!',
//            'update_lName.max' => 'Last name is too long!',
//            'update_Title.required' => 'User Title should be provided!',
//            'update_Title.in' => 'Title Invalid!',
//            'update_fName.required' => 'First Name should be provided!',
//            'update_lName.required' => 'Last Name should be provided!',
//            'update_gender.required' => 'Gender should be provided!',
//            'update_contactNo.required' => 'Contact Number should be provided!',
//            'update_type.required' => 'User Type be provided!',
//            'update_type.exists' => 'User Type invalid!',
//            'update_username.required' => 'Username should be provided!.',
//            'update_username.email' => 'Email format invalid!.',
//            'update_proImage.image'=>'User image format invalid!',
//            'update_proImage.max'=>'User image size should lower than 3MB!',
//
//        ]);
//
//        if ($validator->fails()) {
//            return redirect('view_users')
//                ->withErrors($validator)
//                ->withInput();
//        }
//
//        $name = "";
//        if ($request->hasfile('update_proImage')) {
//            $file = $request->file('update_proImage');
//            $name =  time().$file->getClientOriginalName();
//            $file->move(public_path('assets/images/users/'), $name);
//        }
//
//        $updateUser = User::find(intval($hiddenVID));
//        $updateUser->title = $update_Title;
//        $updateUser->fName = strtoupper($update_fName);
//        $updateUser->Lname = strtoupper($update_lName);
//        $updateUser->gender = $update_gender;
//        $updateUser->contactNo = $update_contactNo;
//        $updateUser->UserRole = $update_type;
//        $updateUser->image = $name;
//        $updateUser->bday = date('Y-m-d', strtotime($dob));
//        $updateUser->username = strtolower($update_username);
//        $updateUser->Company=$update_agency;
//        $updateUser->update();
//
//        return redirect()->route('view_users')->with('success', 'User Information updated successfully.');
//
//    }
//
//    public function updatePassword(Request $request)
//    {
//        $update_pass2 = $request['update_pass2'];
//        $hiddenPID = $request['hiddenPID'];
//        $compass = $request['compass'];
//
//        $validator = \Validator::make($request->all(), [
//            'update_pass2' => 'required',
//            'compass' => 'required',
//
//        ], [
//            'update_pass2.required' => 'Password should be provided!',
//            'compass.required' => 'Conform Password should be provided!',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json(['errors' => $validator->errors()->all()]);
//        }
//        if ($compass != $update_pass2) {
//            return response()->json(['errors' => ['error'=>'Password not match.']]);
//        }
//
//        $advanceEncryption = (new  \App\MyResources\AdvanceEncryption($update_pass2, "Nova6566", 256));
//
//        $pass = User::find(intval($hiddenPID));
//        $pass->password = $advanceEncryption->encrypt();
//        $pass->save();
//
//        return response()->json(['success' => 'User Password is successfully updated']);
//
//    }
//
//
//    public function checkUsername(Request $request)
//    {
//        if (User::where('username',$request['username'])->exists()) {
//            return 1;
//        } else {
//            return 0;
//        }
//
//    }
//
//    public function changeStatus(Request $request){
//        $id = $request['id'];
//        $user = User::find(intval($id));
//        if ($user->status == 1) {
//            $user->status = 0;
//        } else {
//            $user->status = 1;
//        }
//        $user->save();
//    }


}
