<?php

namespace App\Http\Controllers;

use App\Analysis;
use App\Career;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\NatureOfIncome;
use App\Religion;
use App\User;
use App\VotersCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenericReportController extends Controller
{
    public function agents(Request $request)
    {

        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();

        $rows = $request['rows'] != null ? $request['rows'] == 'all' ? 100000 : $request['rows'] : 10;
        $query = User::query();

        $query = $query->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6);

        if ($request['gender'] != null) {
            $query = $query->where('gender', $request['gender']);
        }
        if (!empty($request['searchText'])) {

            if ($request['searchCol'] == 1) {
                $query = $query->where('fName', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 2) {
                $query = $query->where('lName', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 3) {
                $query = $query->where('nic', $request['searchText']);

            } else if ($request['searchCol'] == 4) {
                $query = $query->where('email', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 5) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('referral_code', 'like', '%' . $request['searchText'] . '%');
                });
            }

        }
        if ($request['village'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['pollingBooth'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        } else if ($request['electionDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idelection_division', $request['electionDivision']);
            });
        }

        if ($request['ethnicity'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idethnicity', $request['ethnicity']);
            });
        }
        if ($request['religion'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idreligion', $request['religion']);
            });
        }
        if ($request['income'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idnature_of_income', $request['income']);
            });
        }

        if ($request['education'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('ideducational_qualification', $request['education']);
            });
        }

        if ($request['career'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idcareer', $request['career']);
            });
        }
        if (!empty($request['start']) && !empty($request['end'])) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end']));

            $query = $query->whereBetween('bday', [$startDate, $endDate]);
        }
        if ($request['jobSector'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('is_government', $request['jobSector']);
            });
        }
        $users = $query->latest()->paginate($rows);

        $users->appends([
            'start' => $request['start'],
            'rows' => $request['rows'],
            'end' => $request['end'],
            'searchCol' => $request['searchCol'],
            'searchText' => $request['searchText'],
            'village' => $request['village'],
            'gramasewaDivision' => $request['gramasewaDivision'],
            'pollingBooth' => $request['pollingBooth'],
            'electionDivision' => $request['electionDivision'],
            'ethnicity' => $request['ethnicity'],
            'religion' => $request['religion'],
            'income' => $request['income'],
            'education' => $request['education'],
            'career' => $request['career'],
            'jobSector' => $request['jobSector']
        ]);

        return view('generic_reports.agents')->with(['ethnicities' => $ethnicities, 'educations' => $educations, 'incomes' => $incomes, 'religions' => $religions, 'careers' => $careers, 'users' => $users, 'title' => 'Reports : Agents', 'electionDivisions' => $electionDivisions]);

    }

    public function members(Request $request)
    {

        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();

        $rows = $request['rows'] != null ? $request['rows'] == 'all' ? 100000 : $request['rows'] : 10;
        $query = User::query();

        $query = $query->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 7);

        $query = $query->where(function ($q) {
            $q->whereHas('member', function ($q) {
                $q->whereHas('memberAgents', function ($q) {
                    $q->where('idoffice', Auth::user()->idoffice);
                });
            });
        });

        if ($request['gender'] != null) {
            $query = $query->where('gender', $request['gender']);
        }
        if (!empty($request['searchText'])) {

            if ($request['searchCol'] == 1) {
                $query = $query->where('fName', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 2) {
                $query = $query->where('lName', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 3) {
                $query = $query->where('nic', $request['searchText']);
            } else if ($request['searchCol'] == 4) {
                $query = $query->where('email', 'like', '%' . $request['searchText'] . '%');
            }
        }
        if ($request['village'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['pollingBooth'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        } else if ($request['electionDivision'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idelection_division', $request['electionDivision']);
            });
        }

        if ($request['ethnicity'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idethnicity', $request['ethnicity']);
            });
        }
        if ($request['religion'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idreligion', $request['religion']);
            });
        }
        if ($request['income'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idnature_of_income', $request['income']);
            });
        }

        if ($request['education'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('ideducational_qualification', $request['education']);
            });
        }

        if ($request['career'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idcareer', $request['career']);
            });
        }
        if (!empty($request['start']) && !empty($request['end'])) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end']));

            $query = $query->whereBetween('bday', [$startDate, $endDate]);
        }
        if ($request['jobSector'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('is_government', $request['jobSector']);
            });
        }
        $users = $query->latest()->paginate($rows);

        $users->appends([
            'start' => $request['start'],
            'rows' => $request['rows'],
            'end' => $request['end'],
            'searchCol' => $request['searchCol'],
            'searchText' => $request['searchText'],
            'village' => $request['village'],
            'gramasewaDivision' => $request['gramasewaDivision'],
            'pollingBooth' => $request['pollingBooth'],
            'electionDivision' => $request['electionDivision'],
            'ethnicity' => $request['ethnicity'],
            'religion' => $request['religion'],
            'income' => $request['income'],
            'education' => $request['education'],
            'career' => $request['career'],
            'jobSector' => $request['jobSector']
        ]);

        return view('generic_reports.member')->with(['ethnicities' => $ethnicities, 'educations' => $educations, 'incomes' => $incomes, 'religions' => $religions, 'careers' => $careers, 'users' => $users, 'title' => 'Reports : Members', 'electionDivisions' => $electionDivisions]);

    }

    public function age()
    {
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get();
        return view('generic_reports.age')->with(['electionDivisions'=>$electionDivisions,'title' => 'Report : Age']);
    }

    public function ageChart(Request $request)
    {
        $value = $request['age'] != null ? $request['age'] : 30;
        $agentMin = 0;
        $agentMax = 0;
        $agentEqual = 0;
        $memberMin = 0;
        $memberMax = 0;
        $memberEqual = 0;
        $query = User::query();
        if($request['electionDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                        $q->where('idelection_division', $request['electionDivision']);
                });
        }
        if($request['pollingBooth'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }
        $agents = $query->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();
        if($agents != null) {
            foreach ($agents as $agent) {
                if ($agent->age < $value) {
                    $agentMin += 1;
                } elseif ($agent->age == $value) {
                    $agentEqual += 1;
                } else {
                    $agentMax += 1;
                }
            }
        }
        $agentCount = count($agents);

        $query1 = User::query();
        if($request['electionDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }

        $members = $query1->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        if($members != null) {
            foreach ($members as $member) {
                if ($member->age < $value) {
                    $memberMin += 1;
                } elseif ($member->age == $value) {
                    $memberEqual += 1;
                } else {
                    $memberMax += 1;
                }
            }
        }
        $membersCount = count($members);

        return response()->json(['success' => ['agent_count'=>$agentCount,'member_count'=>$membersCount,'member_equal'=>intval($memberEqual),'agent_equal'=>intval($agentEqual),'agent_min' => intval($agentMin), 'agent_max' => intval($agentMax), 'member_min' => intval($memberMin), 'member_max' => intval($memberMax)]]);

    }

    public function education()
    {
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get();
        return view('generic_reports.education')->with(['electionDivisions'=>$electionDivisions,'title' => 'Report : Education Qualifications']);
    }

    public function educationChart(Request $request)
    {

        $query = User::query();
        if($request['electionDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }
        $agents = $query->with(['agent.educationalQualification'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.ideducational_qualification']);
        $agentCount = count($agents);

        $query1 = User::query();
        if($request['electionDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }

        $members = $query1->with(['member.educationalQualification'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.ideducational_qualification']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents'=>$agentsGroup,'members'=>$membersGroups,'agent_count'=>$agentCount,'member_count'=>$membersCount]]);

    }

    public function income()
    {
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get();
        return view('generic_reports.income')->with(['electionDivisions'=>$electionDivisions,'title' => 'Report : Nature of Income']);
    }

    public function incomeChart(Request $request)
    {

        $query = User::query();
        if($request['electionDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }
        $agents = $query->with(['agent.natureOfIncome'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.idnature_of_income']);
        $agentCount = count($agents);

        $query1 = User::query();
        if($request['electionDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }

        $members = $query1->with(['member.natureOfIncome'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.idnature_of_income']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents'=>$agentsGroup,'members'=>$membersGroups,'agent_count'=>$agentCount,'member_count'=>$membersCount]]);

    }

    public function career()
    {
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get();
        return view('generic_reports.career')->with(['electionDivisions'=>$electionDivisions,'title' => 'Report : Career Type']);
    }

    public function careerChart(Request $request)
    {

        $query = User::query();
        if($request['electionDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }
        $agents = $query->with(['agent.career'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.idcareer']);
        $agentCount = count($agents);

        $query1 = User::query();
        if($request['electionDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }

        $members = $query1->with(['member.career'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.idcareer']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents'=>$agentsGroup,'members'=>$membersGroups,'agent_count'=>$agentCount,'member_count'=>$membersCount]]);

    }

    public function religion()
    {
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get();
        return view('generic_reports.religion')->with(['electionDivisions'=>$electionDivisions,'title' => 'Report : Religion']);
    }

    public function religionChart(Request $request)
    {

        $query = User::query();
        if($request['electionDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }
        $agents = $query->with(['agent.religion'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.idreligion']);
        $agentCount = count($agents);

        $query1 = User::query();
        if($request['electionDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }

        $members = $query1->with(['member.religion'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.idreligion']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents'=>$agentsGroup,'members'=>$membersGroups,'agent_count'=>$agentCount,'member_count'=>$membersCount]]);

    }

    public function ethnicity()
    {
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get();
        return view('generic_reports.ethnicity')->with(['electionDivisions'=>$electionDivisions,'title' => 'Report : Ethnicity']);
    }

    public function ethnicityChart(Request $request)
    {

        $query = User::query();
        if($request['electionDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query = $query->whereHas('agent', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }
        $agents = $query->with(['agent.ethnicity'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.idethnicity']);
        $agentCount = count($agents);

        $query1 = User::query();
        if($request['electionDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idelection_division', $request['electionDivision']);
            });
        }
        if($request['pollingBooth'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        if($request['gramasewaDivision'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        if($request['village'] != null){
            $query1 = $query1->whereHas('member', function ($q) use ($request){
                $q->where('idvillage', $request['village']);
            });
        }

        $members = $query1->with(['member.ethnicity'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.idethnicity']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents'=>$agentsGroup,'members'=>$membersGroups,'agent_count'=>$agentCount,'member_count'=>$membersCount]]);

    }

    public function voters(Request $request){
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get();
        return view('generic_reports.voters')->with(['electionDivisions'=>$electionDivisions,'title' => 'Report : Voters']);

    }

    public function votersChart(Request $request)
    {
        $village = $request['village'];
        $gramasewaDivision = $request['gramasewaDivision'];
        $pollingBooth = $request['pollingBooth'];
        $electionDivision = $request['electionDivision'];

        if ($village != null) {
            $voters = VotersCount::with(['village','village.gramasewaDivision','village.gramasewaDivision.pollingBooth','village.gramasewaDivision.pollingBooth.electionDivision'])->where('idoffice',Auth::user()->idoffice)->where('idvillage',$village)->get();

        } else if ($gramasewaDivision != null) {

            $voters = VotersCount::with(['village','village.gramasewaDivision','village.gramasewaDivision.pollingBooth','village.gramasewaDivision.pollingBooth.electionDivision'])
                ->whereHas('village', function ($q) use($gramasewaDivision){
                    $q->where('idgramasewa_division', $gramasewaDivision);
                })
                ->where('idoffice',Auth::user()->idoffice)->get();

        } else if ($pollingBooth != null) {

            $voters = VotersCount::with(['village','village.gramasewaDivision','village.gramasewaDivision.pollingBooth','village.gramasewaDivision.pollingBooth.electionDivision'])
                ->whereHas('village', function ($q) use($pollingBooth){
                    $q->where('idpolling_booth', $pollingBooth);
                })
                ->where('idoffice',Auth::user()->idoffice)->get();
        } else if ($electionDivision != null) {

            $voters = VotersCount::with(['village','village.gramasewaDivision','village.gramasewaDivision.pollingBooth','village.gramasewaDivision.pollingBooth.electionDivision'])
                ->whereHas('village', function ($q) use($electionDivision){
                    $q->where('idelection_division', $electionDivision);
                })
                ->where('idoffice',Auth::user()->idoffice)->get();
        } else {
            $voters = VotersCount::with(['village','village.gramasewaDivision','village.gramasewaDivision.pollingBooth','village.gramasewaDivision.pollingBooth.electionDivision'])
                ->whereHas('village', function ($q) {
                   $q->where('iddistrict', Auth::user()->office->iddistrict);
                })
                ->where('idoffice',Auth::user()->idoffice)->get();
        }

        return response()->json(['success' => $voters]);
    }

}
