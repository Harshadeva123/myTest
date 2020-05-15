<?php

namespace App\Http\Controllers;

use App\Career;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\NatureOfIncome;
use App\Post;
use App\Religion;
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
        $electionDivisions = ElectionDivision::where('status',1)->where('iddistrict',Auth::user()->office->iddistrict)->get();

        $careers = Career::where('status',1)->get();
        $religions = Religion::where('status',1)->get();
        $incomes = NatureOfIncome::where('status',1)->get();
        $educations = EducationalQualification::where('status',1)->get();
        $ethnicities = Ethnicity::where('status',1)->get();
        return view('post.create_post', ['title' =>  __('Create Post'),'electionDivisions'=>$electionDivisions,'careers'=>$careers,'religions'=>$religions,'incomes'=>$incomes,'educations'=>$educations,'ethnicities'=>$ethnicities]);
    }

    public function store(Request $request){
        $validationMessages = [
            'title_en.required' => 'Title in english should be provided!',
            'text_en.required' => 'Post text in english should be provided!',
            'electionDivisions.exists' => 'Election division invalid!',
            'pollingBooths.exists' => 'Polling booth invalid!',
            'gramasewaDivisions.exists' => 'Gramasewa division invalid!',
            'villages.exists' => 'Village invalid!',
            'ethnicities.exists' => 'Ethnicity invalid!',
            'religions.exists' => 'Religion invalid!',
            'incomes.exists' => 'Nature of income invalid!',
            'educations.exists' => 'Educational qualification invalid!',
            'careers.exists' => 'Career invalid!',
            'expireDate.required' => 'Expire date should be provided!',
            'expireDate.date' => 'Expire date format invalid!',
            'responsePanel.required' => 'Response panel should be provided!',

        ];

        $validator = \Validator::make($request->all(), [
            'title_en' => 'required',
            'text_en' => 'required',
            'electionDivisions' => 'nullable|exists:election_division,idelection_division',
            'pollingBooths' => 'nullable|exists:polling_booth,idpolling_booth',
            'gramasewaDivisions' => 'nullable|exists:gramasewa_division,idgramasewa_division',
            'villages' => 'nullable|exists:village,idvillage',
            'ethnicities' => 'nullable|exists:ethnicity,idethnicty',
            'religions' => 'nullable|exists:religion,idriligion',
            'incomes' => 'nullable|exists:nature_of_income,idnature_of_income',
            'educations' => 'nullable|exists:education_qualification,ideducation_qualification',
            'careers' => 'nullable|exists:career,idcareer',
            'gender' => 'nullable',
            'expireDate' => 'required|date',
            'onlyOnce' => 'nullable',
            'responsePanel' => 'required',


        ],$validationMessages );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $user = Auth::user()->idUser;
        $office = Auth::user()->idoffice;
        $authDistrict = Auth::user()->office->iddistrict;


        //Validation end
        $post = new Post();
        $post->idUser = $user;
        $post->post_no = $post->nextPostNo($office);
        $post->text = $post->$request['text_en']);

        return response()->json(['success' => 'User Registered Successfully!']);

    }
}
