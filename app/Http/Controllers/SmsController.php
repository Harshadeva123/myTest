<?php

namespace App\Http\Controllers;

use App\Career;
use App\Category;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
use App\GroupContacts;
use App\NatureOfIncome;
use App\Office;
use App\PollingBooth;
use App\Religion;
use App\SmsGroup;
use App\SmsLimit;
use App\User;
use App\Village;
use App\WelcomeSms;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmsController extends Controller
{
    public function index()
    {
        $limit = SmsLimit::where('idoffice', Auth::user()->idoffice)->where('status', 1)->first();
        $welcome = WelcomeSms::where('idoffice', Auth::user()->idoffice)->where('status', 1)->latest()->first();
        return view('sms.welcome')->with(['title' => 'Welcome SMS', 'limit' => $limit, 'welcome' => $welcome]);
    }

    public function config()
    {
        $offices = Office::paginate(10);
        return view('sms.define_sms_count')->with(['title' => 'SMS Configurations', 'offices' => $offices]);

    }

    public function limit(Request $request)
    {
        $validationMessages = [
            'updateId.required' => 'Invalid!',
            'limit.numeric' => 'Limit required!',
        ];

        $validator = \Validator::make($request->all(), [
            'updateId' => 'required|numeric',
            'limit' => 'required|numeric',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $limit = SmsLimit::where('idoffice', $request['updateId'])->where('status', 1)->first();
        if ($limit != null) {
            $limit->limit = $request['limit'];
            $limit->save();
        } else {
            $limit = new SmsLimit();
            $limit->idoffice = $request['updateId'];
            $limit->limit = $request['limit'];
            $limit->current = 0;
            $limit->status = 1;
            $limit->save();
        }

        return response()->json(['success' => 'Updated!']);
    }

    public function saveWelcome(Request $request)
    {
        $validationMessages = [
            'message.required' => 'Please enter your welcome message!',
        ];

        $validator = \Validator::make($request->all(), [
            'message' => 'required',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $welcome = WelcomeSms::where('idoffice', Auth::user()->idoffice)->where('status', 1)->latest()->first();

        if ($welcome != null) {
            $welcome->body = $request['message'];
            $welcome->save();
        } else {
            $welcome = new WelcomeSms();
            $welcome->idoffice = Auth::user()->idoffice;
            $welcome->body = $request['message'];
            $welcome->status = 1;
            $welcome->save();
        }
        return response()->json(['success' => 'saved!']);

    }

    public function create(Request $request)
    {
        $electionDivisions = ElectionDivision::where('status', 1)->where('iddistrict', Auth::user()->office->iddistrict)->get();

        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        return view('sms.create_sms', ['title' => __('Create SMS'), 'categories' => $categories, 'electionDivisions' => $electionDivisions, 'careers' => $careers, 'religions' => $religions, 'incomes' => $incomes, 'educations' => $educations, 'ethnicities' => $ethnicities]);

    }

    public function getFilteredUsers(Request $request)
    {
        $validationMessages = [
            'body.required' => 'Message body should be provided!',
        ];

        $validator = \Validator::make($request->all(), [
            'body' => 'required'

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $user = Auth::user()->idUser;
        $office = Auth::user()->idoffice;

        $gramasewaArray = [];
        $pollingArray = [];
        $electionArray = [];

        //category validation

        $districtAll = false;
        $boothAll = false;
        $electionAll = false;
        $gramasewaAll = false;

        //category validation end

        //village level validation
        $villages = $request['villages'];
        if ($villages != null) {
            foreach ($villages as $id) {
                $selected = Village::find(intval($id));
                if ($selected != null) {
                    array_push($gramasewaArray, $selected->idgramasewa_division);
                } else {
                    return response()->json(['errors' => ['error' => 'Villages Invalid!']]);
                }
            }
        } else {
            $gramasewaAll = true;
        }
        //village level validation end

        //Gramasewa level validation
        $gramasewaDivisions = $request['gramasewaDivisions'];
        if ($gramasewaDivisions != null) {
            foreach ($gramasewaDivisions as $id) {
                $selected = GramasewaDivision::find(intval($id));
                if ($selected != null) {
                    array_push($pollingArray, $selected->idpolling_booth);
                } else {
                    return response()->json(['errors' => ['error' => 'Gramasewa divisions Invalid!']]);
                }
            }
        } else {
            $boothAll = true;
        }
        //Gramasewa level validation end

        //Polling booth level validation
        $pollingBooths = $request['pollingBooths'];
        if ($pollingBooths != null) {
            foreach ($pollingBooths as $id) {
                $selected = PollingBooth::find(intval($id));
                if ($selected != null) {
                    array_push($electionArray, $selected->idelection_division);
                } else {
                    return response()->json(['errors' => ['error' => 'Polling booths Invalid!']]);
                }
            }
        } else {
            $electionAll = true;
        }
        //Polling booth level validation end

        //Election division level validation
        $electionDivisions = $request['electionDivisions'];
        if ($electionDivisions == null) {
            $districtAll = true;
        }
        //Election division level validation end

        //-------------------------------------------Validation end---------------------------------------------------//

        $query = User::query();

        $query = $query->where(function ($q) {
            $q->where('idoffice', Auth::user()->idoffice)->orWhereHas('member', function ($q) {
                $q->WhereHas('memberAgents', function ($q) {
                    $q->whereIn('idoffice', [Auth::user()->idoffice])->where('status', 1);
                });
            });
        });

        if ($villages != null) {
            $query->whereHas('member', function ($q) use ($villages) {
                $q->whereIn('idvillage', $villages)->where('isSms', 1);
            });
        } else if ($gramasewaDivisions != null) {
            $query->whereHas('member', function ($q) use ($gramasewaDivisions) {
                $q->whereIn('idgramasewa_division', $gramasewaDivisions)->where('isSms', 1);
            });
        } else if ($pollingBooths != null) {
            $query->whereHas('member', function ($q) use ($pollingBooths) {
                $q->whereIn('idpolling_booth', $pollingBooths)->where('isSms', 1);
            });
        } else {
            $query->whereHas('member', function ($q) {
                $q->where('iddistrict', Auth::user()->office->iddistrict)->where('isSms', 1);
            });
        }

        $careers = $request['careers'];
        if ($careers != null) {
            $query->whereHas('member', function ($q) use ($careers) {
                $q->whereIn('idcareer', $careers)->where('isSms', 1);
            });
        }

        $religions = $request['religions'];
        if ($religions != null) {
            $query->whereHas('member', function ($q) use ($religions) {
                $q->whereIn('idreligion', $religions)->where('isSms', 1);
            });
        }

        $ethnicities = $request['ethnicities'];
        if ($ethnicities != null) {
            $query->whereHas('member', function ($q) use ($ethnicities) {
                $q->whereIn('idethnicity', $ethnicities)->where('isSms', 1);
            });
        }

        $educations = $request['educations'];
        if ($educations != null) {
            $query->whereHas('member', function ($q) use ($educations) {
                $q->whereIn('ideducational_qualification', $educations)->where('isSms', 1);
            });
        }

        $incomes = $request['incomes'];
        if ($incomes != null) {
            $query->whereHas('member', function ($q) use ($incomes) {
                $q->whereIn('idnature_of_income', $incomes)->where('isSms', 1);
            });
        }

        $gender = $request['gender'];
        if ($gender != 0 && $gender != null) {
            $query->where('gender', $gender);
        }

        $jobSector = $request['jobSector'];
        if ($jobSector != 0 && $jobSector != null) {
            $query->whereHas('member', function ($q) use ($jobSector) {
                $q->where('is_government', $jobSector)->where('isSms', 1);
            });
        }


        $users = $query->get();

        if ($users != null) {
            foreach ($users as $key => $value) {
                if ($value->age < $request['minAge'] || $value->age > $request['maxAge']) {
                    $users->forget($key);
                }
            }
        }

        return $users;
    }


    public function getNumberOfReceivers(Request $request)
    {

        $limit = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->limit : 0;
        $used = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->current : 0;

        return response()->json(['success' =>
                ['recipient' => count($this->getFilteredUsers($request)),
                    'totalPages' => 1,
                    'limit' => $limit,
                    'used' => $used,
                ]
            ]
        );

    }

    public function sendBulk(Request $request)
    {
        $recipients = $this->getFilteredUsers($request);
        $results = [];

        if ($this->isCreditsAvailable(count($recipients))) {

            foreach ($recipients as $recipient) {
                $client = new Client();
                $res = $client->get("https://smsserver.textorigins.com/Send_sms?src=CYCLOMAX236&email=cwimagefactory@gmail.com&pwd=cwimagefactory&msg=" . $request->body . "&dst=" . $recipient->contact_no1 . "");
                $results[] = json_decode($res->getBody(), true);
                $this->increaseSmsCount(1);
            }
            return response()->json(['success' => $results]);

        } else {
            return response()->json(['errors' => ['error' => 'You have not enough credits to send these messages.']]);

        }
    }

    public function send($message,$contactNo)
    {
        if ($this->isCreditsAvailable(1)) {

            $client = new Client();
            $res = $client->get("https://smsserver.textorigins.com/Send_sms?src=CYCLOMAX236&email=cwimagefactory@gmail.com&pwd=cwimagefactory&msg=" . $message . "&dst=" . $contactNo . "");
            $results[] = json_decode($res->getBody(), true);
            $this->increaseSmsCount(1);

            return response()->json(['success' => $results]);

        } else {
            return response()->json(['errors' => ['error' => 'You have not enough credits to send these messages.']]);

        }
    }

    public function isCreditsAvailable($count){
        $limit = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->limit : 0;
        $used = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->current : 0;
        if($limit - $used > $count){
            return 1;
        }
        else{
            return 0;
        }
    }

    public function increaseSmsCount($count){
        $limit = SmsLimit::where('idoffice', Auth::user()->idoffice)->where('status', 1)->first();
        $limit->current += $count;
        $limit->save();
    }

    public function createGroup(Request $request){
        $groups = SmsGroup::where('idoffice',Auth::user()->idoffice)->where('status',1)->get();
        return view('sms.create_group')->with(['title' => 'Create SMS Group', 'groups' => $groups]);
    }

    public function getGroupByOffice(){
        $groups  =  SmsGroup::where('idoffice',Auth::user()->idoffice)->latest()->get();
        foreach ($groups as $group){
            $group['count'] = $group->contacts()->count();
        }
        return response()->json(['success' =>$groups]);
    }

    public function storeGroup(Request $request){
        $validationMessages = [
            'group.required' => 'Group name should be provided!',
        ];

        $validator = \Validator::make($request->all(), [
            'group' => 'required'

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $group = new SmsGroup();
        $group->idoffice = Auth::user()->idoffice;
        $group->name = strtoupper($request['group']);
        $group->status = 1;
        $group->save();
        return response()->json(['success' => 'success']);
    }

    public function updateGroup(Request $request){
        $validationMessages = [
            'group.required' => 'Group name should be provided!',
            'updateId.required' => 'Updated Invalid!'
        ];

        $validator = \Validator::make($request->all(), [
            'group' => 'required',
            'updateId' => 'required'
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $id = $request['updateId'];
        $isExist = SmsGroup::find(intval($id));
        if($isExist != null){
            $isExist->name = $request['group'];
            $isExist->save();
            return response()->json(['success' => 'success']);

        }
        else{
            return response()->json(['errors' => ['error' => 'Group Invalid!']]);

        }
    }

    public function deleteGroup(Request $request){

        $group = SmsGroup::where('idoffice',Auth::user()->idoffice)->where('idsms_group',$request['id'])->first();
        if($group != null){
            $group->delete();
            return response()->json(['success' => 'success']);
        }
        else{
            return response()->json(['errors' => ['error' => 'Group Invalid!']]);

        }

    }

    public function addContacts(){
        $groups = SmsGroup::where('idoffice',Auth::user()->idoffice)->where('status',1)->get();
        return view('sms.add_contacts')->with(['title' => 'Add Contacts', 'groups' => $groups]);
    }

    public function getContactByGroup(Request $request){
        $validationMessages = [
            'id.required' => 'Group name should be provided!',
            'id.required' => 'Updated Invalid!'
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'id' => 'required'
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $contacts =  GroupContacts::where('idsms_group',$request['id'])->where('status',1)->get();
        return response()->json(['success' => $contacts]);

    }

    public function storeContact(Request $request){
        $validationMessages = [
            'group.required' => 'Group invalid!',
            'contact.required' => 'Updated Invalid!',
            'contact.min' => 'Contact should be 10 numbers long',
            'contact.numeric' => 'Contact can only contains numbers'
        ];

        $validator = \Validator::make($request->all(), [
            'contact' => 'required|min:10|numeric',
            'group' => 'required'
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $contact = new GroupContacts();
        $contact->idsms_group = $request['group'];
        $contact->contact = $request['contact'];
        $contact->status = 1;
        $contact->save();
        return response()->json(['success' => 'success']);

    }

    public function updateContact(Request $request){
        $validationMessages = [
            'contact.required' => 'Contact number should be provided!',
            'updateId.required' => 'Updated Invalid!'
        ];

        $validator = \Validator::make($request->all(), [
            'contact' => 'required',
            'updateId' => 'required'
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $id = $request['updateId'];
        $isExist = GroupContacts::find(intval($id));
        if($isExist != null){
            $isExist->contact = $request['contact'];
            $isExist->save();
            return response()->json(['success' => 'success']);

        }
        else{
            return response()->json(['errors' => ['error' => 'Update Invalid!']]);

        }
    }

    public function deleteContact(Request $request){

        $contact = GroupContacts::find($request['id']);
        if($contact != null){
            $contact->delete();
            return response()->json(['success' => 'success']);
        }
        else{
            return response()->json(['errors' => ['error' => 'Process Invalid!']]);

        }

    }

    public function sendGroup(Request $request){
        $group = SmsGroup::where('idoffice',Auth::user()->idoffice)->where('status',1)->get();
        return view('sms.broadcast_group', ['title' => __('Send Group'), 'groups' => $group]);

    }

    public function getNumberOfReceiversGroup(Request $request)
    {

        $group = $request['group'];
        $limit = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->limit : 0;
        $used = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->current : 0;

        return response()->json(['success' =>
                ['recipient' => SmsGroup::find(intval($group))->contacts()->count(),
                    'totalPages' => 1,
                    'limit' => $limit,
                    'used' => $used,
                ]
            ]
        );

    }

}
