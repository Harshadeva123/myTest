<?php

namespace App\Http\Controllers;

use App\Career;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
use App\NatureOfIncome;
use App\PollingBooth;
use App\Post;
use App\PostCareer;
use App\PostDistrict;
use App\PostEducation;
use App\PostElectionDivision;
use App\PostEthnicity;
use App\PostGramasewaDivision;
use App\PostIncome;
use App\PostPollingBooth;
use App\PostReligion;
use App\PostVillage;
use App\Religion;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *render 'create post' interface
     */
    public function index()
    {
        $electionDivisions = ElectionDivision::where('status', 1)->where('iddistrict', Auth::user()->office->iddistrict)->get();

        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();
        return view('post.create_post', ['title' => __('Create Post'), 'electionDivisions' => $electionDivisions, 'careers' => $careers, 'religions' => $religions, 'incomes' => $incomes, 'educations' => $educations, 'ethnicities' => $ethnicities]);
    }

    public function store(Request $request)
    {
        $validationMessages = [
            'title_en.required' => 'Title in english should be provided!',
            'text_en.required' => 'Post text in english should be provided!',
            'expireDate.required' => 'Expire date should be provided!',
            'expireDate.date' => 'Expire date format invalid!',
            'responsePanel.required' => 'Response panel should be provided!',

        ];

        $validator = \Validator::make($request->all(), [
            'title_en' => 'required',
            'text_en' => 'required',
            'gender' => 'nullable',
            'expireDate' => 'required|date',
            'onlyOnce' => 'nullable',
            'responsePanel' => 'required',


        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $user = Auth::user()->idUser;
        $office = Auth::user()->idoffice;
        $authDistrict = Auth::user()->office->iddistrict;

        $gramasewaArray = [];
        $pollingArray = [];
        $electionArray = [];

        //village level validation
        $villages = $request['villages'];
        if ($villages != null) {
            foreach ($villages as $id) {
                $selected = Village::find(intval($id));
                if ($selected != null) {
                    $gramasewaArray += $selected->idgramasewa_division;
                } else {
                    return response()->json(['errors' => ['error' => 'Villages Invalid!']]);
                }
            }
        }
        //village level validation end

        //Gramasewa level validation
        $gramasewaDivisions = $request['gramasewaDivisions'];
        if ($gramasewaDivisions != null) {
            foreach ($gramasewaDivisions as $id) {
                $selected = GramasewaDivision::find(intval($id));
                if ($selected != null) {
                    $pollingArray += $selected->idpolling_booth;
                } else {
                    return response()->json(['errors' => ['error' => 'Gramasewa divisions Invalid!']]);
                }
            }
        }
        //Gramasewa level validation end

        //Polling booth level validation
        $pollingBooths = $request['pollingBooths'];
        if ($pollingBooths != null) {
            foreach ($pollingBooths as $id) {
                $selected = PollingBooth::find(intval($id));
                if ($selected != null) {
                    $electionArray += $selected->idelection_division;
                } else {
                    return response()->json(['errors' => ['error' => 'Polling booths Invalid!']]);
                }
            }
        }
        //Polling booth level validation end

        //Validation end


        //save in post table
        $post = new Post();
        $post->idUser = $user;
        $post->post_no = $post->nextPostNo($office);
        $post->title_en = $request['title_en'];
        $post->title_si = $request['title_si'];
        $post->title_si = $request['title_si'];
        $post->title_ta = $request['title_ta'];
        $post->text_en = $request['text_en'];
        $post->text_si = $request['text_si'];
        $post->text_ta = $request['text_ta'];
        $post->careers = $request['careers'] == null ? 0 : 1;
        $post->religions = $request['religions'] == null ? 0 : 1;
        $post->ethnicities = $request['ethnicities'] == null ? 0 : 1;
        $post->educations = $request['educations'] == null ? 0 : 1;
        $post->incomes = $request['incomes'] == null ? 0 : 1;
        $post->isOnce = $request['onlyOnce'] == 'on' ? 1 : 0;
        $post->job_sector = $request['jobSector'];
        $post->preferred_gender = $request['gender'];
        $post->minAge = intval($request['minAge']);
        $post->maxAge = intval($request['maxAge']);
        $post->response_panel = $request['responsePanel'];
        $post->response_panel = $request['responsePanel'];
        $post->categorized = 0;// uncategorized
        $post->status = 1;//active post
        $post->expire_date = date('Y-m-d', strtotime($request['expireDate']));
        $post->save();
        //save in post table end

        //save in community tables
        $careers = $request['careers'];
        if ($careers != null) {
            foreach ($careers as $career) {
                $postCareer = new PostCareer();
                $postCareer->idPost = $post->idPost;
                $postCareer->idcareer = $career;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }

        $religions = $request['religions'];
        if ($religions != null) {
            foreach ($religions as $religion) {
                $postCareer = new PostReligion();
                $postCareer->idPost = $post->idPost;
                $postCareer->idreligion = $religion;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }

        $ethnicities = $request['ethnicities'];
        if ($ethnicities != null) {
            foreach ($ethnicities as $ethnicity) {
                $postCareer = new PostEthnicity();
                $postCareer->idPost = $post->idPost;
                $postCareer->idethnicity = $ethnicity;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }

        $educations = $request['educations'];
        if ($educations != null) {
            foreach ($educations as $education) {
                $postCareer = new PostEducation();
                $postCareer->idPost = $post->idPost;
                $postCareer->ideducational_qualification = $education;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }

        $incomes = $request['incomes'];
        if ($incomes != null) {
            foreach ($incomes as $income) {
                $postCareer = new PostIncome();
                $postCareer->idPost = $post->idPost;
                $postCareer->idnature_of_income = $income;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }
        //save in community tables end

        //save in hierarchy tables
        $electionDivisions = $request['electionDivisions'];
        if (!empty($electionDivisions)) {

            $postDistrict = new PostDistrict();
            $postDistrict->idPost = $post->idPost;
            $postDistrict->iddistrict = Auth::user()->office->iddistrict;
            $postDistrict->allChild = 0;
            $postDistrict->status = 1;
            $postDistrict->save();

            foreach ($electionDivisions as $electionDivision) {
                $postCareer = new PostElectionDivision();
                $postCareer->idPost = $post->idPost;
                $postCareer->idelection_division = $electionDivision;
                if (in_array($electionDivision, $electionArray)) {
                    $postCareer->allChild = 0;
                } else {
                    $postCareer->allChild = 1;
                }
                $postCareer->status = 1;
                $postCareer->save();

            }
        } else {
            $postDistrict = new PostDistrict();
            $postDistrict->idPost = $post->idPost;
            $postDistrict->iddistrict = Auth::user()->office->iddistrict;
            $postDistrict->allChild = 1;
            $postDistrict->status = 1;
            $postDistrict->save();
        }


        $pollingBooths = $request['pollingBooths'];
        if (!empty($pollingBooths)) {
            foreach ($pollingBooths as $id) {
                $postPolling = new PostPollingBooth();
                $postPolling->idPost = $post->idPost;
                $postPolling->idpolling_booth = $id;
                if (in_array($id, $pollingArray)) {
                    $postPolling->allChild = 0;
                } else {
                    $postPolling->allChild = 1;
                }
                $postPolling->status = 1;
                $postPolling->save();

            }
        }

        $gramasewaDivisions = $request['gramasewaDivisions'];
        if (!empty($gramasewaDivisions)) {
            foreach ($gramasewaDivisions as $id) {
                $postGramasewa = new PostGramasewaDivision();
                $postGramasewa->idPost = $post->idPost;
                $postGramasewa->idgramasewa_division = $id;
                if (in_array($id, $gramasewaArray)) {
                    $postGramasewa->allChild = 0;
                } else {
                    $postGramasewa->allChild = 1;
                }
                $postGramasewa->status = 1;
                $postGramasewa->save();

            }
        }

        $villages = $request['villages'];
        if (!empty($villages)) {
            foreach ($villages as $id) {
                $postVillage = new PostVillage();
                $postVillage->idPost = $post->idPost;
                $postVillage->idvillage = $id;
                $postVillage->status = 1;
                $postVillage->save();
            }
        }

        //save in hierarchy tables end

        return response()->json(['success' => 'User Registered Successfully!']);

    }
}
