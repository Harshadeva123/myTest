<?php

namespace App\Http\Controllers;

use App\Career;
use App\EducationalQualification;
use App\Ethnicity;
use App\NatureOfIncome;
use App\Religion;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(){
        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();
        $agents = User::where('idoffice',Auth::user()->idoffice)->where('iduser_role',6)->where('status',1)->paginate(10);
        return view('task.assign_task')->with(['title'=>'Assign Task','agents'=>$agents, 'careers' => $careers, 'religions' => $religions, 'incomes' => $incomes, 'educations' => $educations, 'ethnicities' => $ethnicities]);
    }

    public function view(Request $request)
    {
        $searchCol = $request['searchCol'];
        $searchText = $request['searchText'];
        $endDate = $request['end'];
        $startDate = $request['start'];

        $query = Task::query();
        if (!empty($searchText)) {
            if($searchCol == 1){
                $query = $query->whereHas('user', function($q) use($searchText){
                    $q->where('fName',  'like', '%' . $searchText . '%');
                });
            }
            else if($searchCol == 2){
                $query = $query->whereHas('user', function($q) use($searchText){
                    $q->where('lName',  'like', '%' . $searchText . '%');
                });
            }
        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end']. ' +1 day'));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $tasks = $query->where('assigned_by', Auth::user()->idUser)->where('status', 1)->latest()->paginate(10);

        return view('task.view_tasks', ['title' =>  __('View Tasks'), 'tasks' => $tasks]);
    }

    public function getById(Request $request){
       $id =  $request['id'];
       if($id != null){
           $task = Task::where('assigned_by',Auth::user()->idUser)->where('idtask',$id)->first();
           if($task != null){
               return response()->json(['success' => $task]);
           }
           else{
               return response()->json(['errors' => ['error'=>'Invalid task.']]);
           }
       }
       else{
           return response()->json(['errors' => ['error'=>'Invalid task.']]);
       }
    }
}
