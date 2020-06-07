<?php

namespace App\Http\Controllers\Api;

use App\Task;
use App\VotersCount;
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
        $tasks = Task::with(['apiEthnicities','apiCareers','apiEducation','apiReligion','apiAge','apiIncome'])->where('idUser',Auth::user()->idUser)->where('status',2)->latest()->get();

        foreach ($tasks as $task){
            if($task->task_gender == 0){
                $gender = 'Any';
            }
            else if($task->task_gender == 1){
                $gender = 'Male';
            }
            else if($task->task_gender == 2){
                $gender = 'Female';
            }
            else if($task->task_gender == 3){
                $gender = 'Other';
            }
            else{
                $gender = '';
            }

            if($task->task_job_sector == 0){
                $jobSector = 'Any';
            }
            else if($task->task_job_sector == 1){
                $jobSector = 'Government';
            }
            else if($task->task_job_sector == 2){
                $jobSector = 'Private';
            }
            else if($task->task_job_sector == 3){
                $jobSector = 'Non-Government';
            }
            else{
                $jobSector = '';
            }

            $task['gender'] = $gender;
            $task['jobSector'] = $jobSector;


            $ethnicities=json_decode($task->apiEthnicities,true);
            $ethnicities=array_column($ethnicities,'name');
            $task['ethnicities']=implode(',',$ethnicities);

            $careers = json_decode($task->apiCareers,true);
            $careers=array_column($careers,'name');
            $task['careers']=implode(',',$careers);

            $education = json_decode($task->apiEducation,true);
            $education=array_column($education,'name');
            $task['educations']=implode(',',$education);

            $religion = json_decode($task->apiReligion,true);
            $religion=array_column($religion,'name');
            $task['religion']=implode(',',$religion);

            $income = json_decode($task->apiIncome,true);
            $income=array_column($income,'name');
            $task['income']=implode(',',$income);

            if($task->apiAge != null) {
                if ($task->apiAge['comparison'] == 0) {
                    $age = 'Equal To '.$task->apiAge['minAge'];
                } else if ($task->apiAge['comparison'] == 1) {
                    $age = 'Less Than '.$task->apiAge['minAge'];

                } else if ($task->apiAge['comparison'] == 2) {
                    $age = 'Grater Than '.$task->apiAge['minAge'];

                } else if ($task->apiAge['comparison'] == 3) {
                    $age = 'Between '.$task->apiAge['minAge'] . ' - ' . $task->apiAge['maxAge'];
                }
                else{
                    $age = null;
                }
            }
            else{
                $age = null;
            }
            $task['age'] = $age;

            $task->makeHidden('apiEthnicities')->toArray();
            $task->makeHidden('apiCareers')->toArray();
            $task->makeHidden('task_job_sector')->toArray();
            $task->makeHidden('task_gender')->toArray();
            $task->makeHidden('apiEducation')->toArray();
            $task->makeHidden('apiReligion')->toArray();
            $task->makeHidden('apiIncome')->toArray();
            $task->makeHidden('updated_at')->toArray();
            $task->makeHidden('idUser')->toArray();
            $task->makeHidden('apiAge')->toArray();
            $task->makeHidden('idoffice')->toArray();
            $task->makeHidden('assigned_by')->toArray();
            $task->makeHidden('isDefault')->toArray();
//            $task->makeHidden('ethnicities.idtask_ethnicity')->toArray();
        }
        return response()->json(['success' => $tasks, 'statusCode' => 0]);
    }

    public function storeVotersCount(Request $request){

        if (Auth::user()->iduser_role != 6) {
            return response()->json(['error' => 'You are not an agent', 'statusCode' => -99]);
        }

        $voters = VotersCount::where('idoffice',Auth::user()->idoffice)->where('status',1)->first();
        if($voters != null){
            $voters->total= $request['total'];
            $voters->forecasting= $request['forecasting'];
            $voters->houses= $request['noOfHouses'];
            $voters->save();
        }
        else{
            $voters = new VotersCount();
            $voters->idvillage = Auth::user()->agent->idvillage;
            $voters->idoffice = Auth::user()->idoffice;
            $voters->idUser = Auth::user()->idUser;
            $voters->total= $request['total'];
            $voters->forecasting= $request['forecasting'];
            $voters->houses= $request['noOfHouses'];
            $voters->status  = 1;
            $voters->save();
        }


        return response()->json(['success' => 'Value saved', 'statusCode' => 0]);

    }


}
