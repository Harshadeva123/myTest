<?php

namespace App\Http\Controllers;

use App\Career;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\NatureOfIncome;
use App\Religion;
use App\User;
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
        }
        else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        else if ($request['pollingBooth'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        else if ($request['electionDivision'] != null) {
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
        }
        else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        }
        else if ($request['pollingBooth'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idpolling_booth', $request['pollingBooth']);
            });
        }
        else if ($request['electionDivision'] != null) {
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

}
