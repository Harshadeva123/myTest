<?php

namespace App\Http\Controllers;

use App\Career;
use App\Category;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
use App\NatureOfIncome;
use App\Office;
use App\PollingBooth;
use App\Religion;
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
//        $client = new Client();
//        $res = $client->get("https://smsserver.textorigins.com/Send_sms?src=CYCLOMAX236&email=cwimagefactory@gmail.com&pwd=cwimagefactory&msg=TestMassage&dst=0717275539");
//        $result =  json_decode($res->getBody(),true);
//        return $result;
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

        $query = $query->where('idoffice',Auth::user()->idoffice);

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
        $query->whereHas('memberAgents', function ($q) use ($religions) {
            $q->whereIn('idoffice', $religions)->where('status', 1);
        });

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

    public function send(Request $request)
    {
        $limit = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->limit : 0;
        $used = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->current : 0;
        $recipients = $this->getFilteredUsers($request);

        $results = [];
        if ($limit - $used > count($recipients)) {


            foreach ($recipients as $recipient) {
                $client = new Client();
                $res = $client->get("https://smsserver.textorigins.com/Send_sms?src=CYCLOMAX236&email=cwimagefactory@gmail.com&pwd=cwimagefactory&msg=".$request->body."&dst=".$recipient->contact_no1."");
                $result = json_decode($res->getBody(), true);
                $results[] = $result;

                $limit = SmsLimit::where('idoffice', Auth::user()->idoffice)->where('status', 1)->first();
                $limit->current += 1;
                $limit->save();

            }

            return response()->json(['success' => $results]);

        } else {
            return response()->json(['errors' => ['error' => 'You have not enough credits to send these messages.']]);

        }
    }
}
