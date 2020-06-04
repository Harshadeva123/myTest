<?php

namespace App\Http\Controllers\Api;

use App\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiTaskController extends Controller
{
    public function index(Request $request){
        if(Auth::user()->iduser_role != 6){
            return response()->json(['error' => 'You are not aa agent','statusCode'=>-99]);
        }
        if ($request['lang'] == 'si') {
            $lang = 'title_si';
            $langText = 'text_si';
        } elseif ($request['lang'] == 'ta') {
            $lang = 'title_ta';
            $langText = 'text_ta';
        } else {
            $lang = 'title_en';
            $langText = 'text_en';
        }
        $tasks = Task::with(['apiEthnicities','apiCareers','apiEducation','apiReligion','apiAge','apiIncome'])->where('idUser',Auth::user()->idUser)->where('status',1)->latest()->get();

        foreach ($tasks as $task){

            $task->makeHidden('created_at')->toArray();
            $task->makeHidden('idUser')->toArray();
            $task->makeHidden('idoffice')->toArray();
            $task->makeHidden('assigned_by')->toArray();
            $task->makeHidden('isDefault')->toArray();
//            $task->makeHidden('ethnicities.idtask_ethnicity')->toArray();
        }


        return response()->json(['success' => $tasks, 'statusCode' => 0]);
    }
}
