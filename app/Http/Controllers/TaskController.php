<?php

namespace App\Http\Controllers;

use App\Career;
use App\EducationalQualification;
use App\Ethnicity;
use App\Member;
use App\NatureOfIncome;
use App\Position;
use App\Religion;
use App\Task;
use App\TaskAge;
use App\TaskBranchSociety;
use App\TaskCareer;
use App\TaskEducation;
use App\TaskEthnicity;
use App\TaskGender;
use App\TaskIncome;
use App\TaskJobSector;
use App\TaskReligion;
use App\TaskTypes;
use App\TaskWomens;
use App\TaskYouth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {

        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();
        $positions = Position::where('status', 1)->get();
        $searchCol = $request['searchCol'];
        $searchText = $request['searchText'];
        $query = User::query();
        if (!empty($searchText)) {
            if ($searchCol == 1) {
                $query = $query->where('fName', 'like', '%' . $searchText . '%');
            } else if ($searchCol == 2) {
                $query = $query->where('lName', 'like', '%' . $searchText . '%');
            } else if ($searchCol == 3) {
                $query = $query->whereHas('agent', function ($q) use ($searchText) {
                    $q->whereHas('village', function ($q) use ($searchText) {
                        $q->where('name_en', 'like', '%' . $searchText . '%');
                    });
                });
            }
        }
        $agents = $query->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->paginate(10);
        return view('task.assign_task')->with(['positions' => $positions, 'title' => 'Assign Budget', 'agents' => $agents, 'careers' => $careers, 'religions' => $religions, 'incomes' => $incomes, 'educations' => $educations, 'ethnicities' => $ethnicities]);
    }

    public function storeDefault(Request $request)
    {

        $validationMessages = [
            'totalBudget.required' => 'Number of members should be provided!',
            'taskType.required' => 'Task Type should be provided!',
            'totalBudget.not_in' => 'Number of members should be grater than zero (0)!',
        ];

        $validator = \Validator::make($request->all(), [
            'totalBudget' => 'required|not_in:0',
            'taskType' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $userId = Auth::user()->idUser;
        $isEthnicity = $request['isEthnicity'] == 'true' ? $request['isEthnicity'] : 0;
        $isReligion = $request['isReligion'] == 'true' ? $request['isReligion'] : 0;
        $isCareer = $request['isCareer'] == 'true' ? $request['isCareer'] : 0;
        $isIncome = $request['isIncome'] == 'true' ? $request['isIncome'] : 0;
        $isEducational = $request['isEducational'] == 'true' ? $request['isEducational'] : 0;
        $isJobSector = $request['isJobSector'] == 'true' ? $request['isJobSector'] : 0;
        $isGender = $request['isGender'] == 'true' ? $request['isGender'] : 0;
        $isBranch = $request['isBranch'] == 'true' ? $request['isBranch'] : 0;
        $isWomens = $request['isWomens'] == 'true' ? $request['isWomens'] : 0;
        $isYouth = $request['isYouth'] == 'true' ? $request['isYouth'] : 0;
        //Validation end


        $isExist = Task::where('idoffice', Auth::user()->idoffice)->where('idtask_type', $request['taskType'])->where('isDefault', 1)->first();
        if ($isExist != null) {
            $isExist->ethnicities()->delete();
            $isExist->careers()->delete();
            $isExist->incomes()->delete();
            $isExist->religions()->delete();
            $isExist->educations()->delete();
            $isExist->gender()->delete();
            $isExist->job()->delete();
            $isExist->youthSociety()->delete();
            $isExist->womensSociety()->delete();
            $isExist->branchSociety()->delete();
            $isExist->age()->delete();
            $isExist->delete();
        }


        //save in task table
        $task = new Task();
        $task->idUser = $userId;
        $task->assigned_by = Auth::user()->idUser;
        $task->idoffice = Auth::user()->idoffice;
        $task->idtask_type = $request['taskType'];
        $task->isDefault = 1;
        $task->ethnicity = $isEthnicity;
        $task->religion = $isReligion;
        $task->career = $isCareer;
        $task->income = $isIncome;
        $task->education = $isEducational;
        $task->job_sector = $isJobSector;
        $task->gender = $isGender;
        $task->branch = $isBranch;
        $task->womens = $isWomens;
        $task->youth = $isYouth;
        $task->target = intval($request['totalBudget']);
        $task->completed_amount = 0;
        $task->status = 1;
        $task->save();
        //save in task table end



//        if ($isReligion != null) {
//            $religions = $request['religionArray'];
//            if ($religions != null) {
//                foreach ($religions as $religion) {
//                    $taskCareer = new TaskEthnicity();
//                    $taskCareer->idtask = $task->idtask;
//                    $taskCareer->idethnicity = $ethnicity['id'];
//                    $taskCareer->value = $ethnicity['value'];
//                    $taskCareer->completed = 0;
//                    $taskCareer->status = 1;
//                    $taskCareer->save();
//
//                }
//            }
//        }

        //save in community tables
//        if ($request['minAge'] != null) {
//            $taskAge = new TaskAge();
//            $taskAge->idtask = $task->idtask;
//            $taskAge->comparison = $request['ageComparison'];
//            $taskAge->minAge = $request['minAge'];
//            $taskAge->maxAge = $request['maxAge'];
//            $taskAge->status = 1;
//            $taskAge->save();
//        }


        if ($isEthnicity != null) {
            $ethnicities = $request['ethnicityArray'];
            if ($ethnicities != null) {
                foreach ($ethnicities as $ethnicity) {
                    $taskEthnicity = new TaskEthnicity();
                    $taskEthnicity->idtask = $task->idtask;
                    $taskEthnicity->idethnicity = $ethnicity['id'];
                    $taskEthnicity->value = $ethnicity['value'];
                    $taskEthnicity->completed = 0;
                    $taskEthnicity->status = 1;
                    $taskEthnicity->save();

                }
            }
        }

        if ($isCareer != null) {
            $careers = $request['careerArray'];

            if ($careers != null) {
                foreach ($careers as $career) {
                    $taskCareer = new TaskCareer();
                    $taskCareer->idtask = $task->idtask;
                    $taskCareer->idcareer = $career['id'];
                    $taskCareer->value = $career['value'];
                    $taskCareer->completed = 0;
                    $taskCareer->status = 1;
                    $taskCareer->save();


                }
            }
        }

        if ($isReligion != null) {
            $religions = $request['religionArray'];
            if ($religions != null) {
                foreach ($religions as $religion) {
                    $taskReligion = new TaskReligion();
                    $taskReligion->idtask = $task->idtask;
                    $taskReligion->idreligion = $religion['id'];
                    $taskReligion->value = $religion['value'];
                    $taskReligion->completed = 0;
                    $taskReligion->status = 1;
                    $taskReligion->save();
                }
            }
        }

        if($isEducational) {
            $educations = $request['educationArray'];
            if ($educations != null) {
                foreach ($educations as $education) {
                    $taskEducation = new TaskEducation();
                    $taskEducation->idtask = $task->idtask;
                    $taskEducation->ideducational_qualification = $education['id'];
                    $taskEducation->value = $education['value'];
                    $taskEducation->completed = 0;
                    $taskEducation->status = 1;
                    $taskEducation->save();
                }
            }
        }

        if($isIncome) {
            $incomes = $request['incomeArray'];
            if ($incomes != null) {
                foreach ($incomes as $income) {
                    $taskIncome = new TaskIncome();
                    $taskIncome->idtask = $task->idtask;
                    $taskIncome->idnature_of_income = $income['id'];
                    $taskIncome->value = $income['value'];
                    $taskIncome->completed = 0;
                    $taskIncome->status = 1;
                    $taskIncome->save();
                }
            }
        }

        if($isGender) {
            $genders = $request['genderArray'];
            if ($genders != null) {
                foreach ($genders as $gender) {
                    $taskIncome = new TaskGender();
                    $taskIncome->idtask = $task->idtask;
                    $taskIncome->gender = $gender['id'];
                    $taskIncome->value = $gender['value'];
                    $taskIncome->completed = 0;
                    $taskIncome->status = 1;
                    $taskIncome->save();
                }
            }
        }

        if($isJobSector) {
            $jobs = $request['jobSectorArray'];
            if ($jobs != null) {
                foreach ($jobs as $job) {
                    $taskJob = new TaskJobSector();
                    $taskJob->idtask = $task->idtask;
                    $taskJob->job_sector = $job['id'];
                    $taskJob->value = $job['value'];
                    $taskJob->completed = 0;
                    $taskJob->status = 1;
                    $taskJob->save();
                }
            }
        }

        if($isBranch) {
            $branches = $request['branchArray'];
            if ($branches != null) {
                foreach ($branches as $branche) {
                    $taskJob = new TaskBranchSociety();
                    $taskJob->idtask = $task->idtask;
                    $taskJob->idposition = $branche['id'];
                    $taskJob->value = $branche['value'];
                    $taskJob->completed = 0;
                    $taskJob->status = 1;
                    $taskJob->save();
                }
            }
        }

        if($isWomens) {
            $womens = $request['womenArray'];
            if ($womens != null) {
                foreach ($womens as $women) {
                    $taskJob = new TaskWomens();
                    $taskJob->idtask = $task->idtask;
                    $taskJob->idposition = $women['id'];
                    $taskJob->value = $women['value'];
                    $taskJob->completed = 0;
                    $taskJob->status = 1;
                    $taskJob->save();
                }
            }
        }

        if($isYouth) {
            $youths = $request['youthArray'];
            if ($youths != null) {
                foreach ($youths as $youth) {
                    $taskJob = new TaskYouth();
                    $taskJob->idtask = $task->idtask;
                    $taskJob->idposition = $youth['id'];
                    $taskJob->value = $youth['value'];
                    $taskJob->completed = 0;
                    $taskJob->status = 1;
                    $taskJob->save();
                }
            }
        }

        //save in community tables end
        return response()->json(['success' => 'Default task saved successfully']);

    }


    public function view(Request $request)
    {
        $searchCol = $request['searchCol'];
        $searchText = $request['searchText'];
        $endDate = $request['end'];
        $startDate = $request['start'];

        $query = Task::query();
        if (!empty($searchText)) {
            if ($searchCol == 1) {
                $query = $query->whereHas('user', function ($q) use ($searchText) {
                    $q->where('fName', 'like', '%' . $searchText . '%');
                });
            } else if ($searchCol == 2) {
                $query = $query->whereHas('user', function ($q) use ($searchText) {
                    $q->where('lName', 'like', '%' . $searchText . '%');
                });
            }
        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $tasks = $query->where('assigned_by', Auth::user()->idUser)->where('isDefault', 0)->latest()->paginate(10);

        return view('task.view_tasks', ['title' => __('View Budget'), 'tasks' => $tasks]);
    }

    public function getById(Request $request)
    {
        $id = $request['id'];
        if ($id != null) {
            $task = Task::with(['ethnicities', 'ethnicities.ethnicity', 'careers', 'careers.career', 'religions', 'religions.religion', 'incomes', 'incomes.income', 'educations', 'educations.education', 'age'])->where('assigned_by', Auth::user()->idUser)->where('idtask', $id)->first();
            if ($task != null) {
                return response()->json(['success' => $task]);
            } else {
                return response()->json(['errors' => ['error' => 'Invalid task.']]);
            }
        } else {
            return response()->json(['errors' => ['error' => 'Invalid task.']]);
        }
    }

    public function store(Request $request)
    {

        $validationMessages = [
            'userId.required' => 'Invalid agent!',
            'userId.numeric' => 'Invalid agent!',
            'members.required' => 'Number of members should be provided!',
            'members.not_in' => 'Number of members should be grater than zero (0)!',
            'ageComparison.required' => 'Age invalid.Please refresh page!',
            'ageComparison.numeric' => 'Age invalid.Please refresh page!',
            'minAge.numeric' => 'Age should be numeric!',
            'maxAge.numeric' => 'Age should be numeric!',
            'gender.numeric' => 'Invalid gender.please refresh page!',
            'jobSector.numeric' => 'Invalid job sector.please refresh page!',
            'gender.required' => 'Gender should be provided!',
            'jobSector.required' => 'Job sector should be provided!',
        ];

        $validator = \Validator::make($request->all(), [
            'userId' => 'required|numeric',
            'members' => 'required|not_in:0',
            'ageComparison' => 'required|numeric',
            'minAge' => 'nullable|numeric',
            'maxAge' => 'nullable|numeric',
            'religions.*' => 'nullable|',
            'ethnicities.*' => 'nullable|',
            'incomes.*' => 'nullable|',
            'educations.*' => 'nullable|',
            'careers.*' => 'nullable|',
            'gender' => 'required|numeric',
            'jobSector' => 'required|numeric',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request['minAge'] >= $request['maxAge'] && $request['ageComparison'] == 3) {
            return response()->json(['errors' => ['error' => 'Min Age can not be equal or grater than max age!']]);
        }

        $isDefault = $request['isDefault'] == 1 ? 1 : 0;
        if ($isDefault) {
            $userId = null;
            $status = 3;
            $isExist = Task::where('idoffice', Auth::user()->idoffice)->where('isDefault', 1)->first();
            if ($isExist != null) {
                $isExist->ethnicities()->delete();
                $isExist->careers()->delete();
                $isExist->incomes()->delete();
                $isExist->religions()->delete();
                $isExist->educations()->delete();
                $isExist->age()->delete();
                $isExist->delete();
            }
        } else {
            $userId = $request['userId'];
            $status = 2;// pending

        }
        //Validation end


        //save in task table
        $task = new Task();
        $task->idUser = $userId;
        $task->assigned_by = Auth::user()->idUser;
        $task->idoffice = Auth::user()->idoffice;
        $task->task_no = $task->getNextNo();
        $task->isDefault = $isDefault;
        $task->target = intval($request['members']);
        $task->task_gender = $request['gender'];
        $task->task_job_sector = intval($request['jobSector']);
        $task->task_job_sector = intval($request['jobSector']);
        $task->completed_amount = 0;
        $task->description = $request['description'];
        $task->status = $status;
        $task->save();
        //save in task table end

        //save in community tables
        if ($request['minAge'] != null) {
            $taskAge = new TaskAge();
            $taskAge->idtask = $task->idtask;
            $taskAge->comparison = $request['ageComparison'];
            $taskAge->minAge = $request['minAge'];
            $taskAge->maxAge = $request['maxAge'];
            $taskAge->status = 1;
            $taskAge->save();
        }

        $careers = $request['careers'];
        if ($careers != null) {
            foreach ($careers as $career) {
                $taskCareer = new TaskCareer();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->idcareer = $career;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }

        $religions = $request['religions'];
        if ($religions != null) {
            foreach ($religions as $religion) {
                $taskCareer = new TaskReligion();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->idreligion = $religion;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }

        $ethnicities = $request['ethnicities'];
        if ($ethnicities != null) {
            foreach ($ethnicities as $ethnicity) {
                $taskCareer = new TaskEthnicity();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->idethnicity = $ethnicity;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }

        $educations = $request['educations'];
        if ($educations != null) {
            foreach ($educations as $education) {
                $taskCareer = new TaskEducation();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->ideducational_qualification = $education;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }

        $incomes = $request['incomes'];
        if ($incomes != null) {
            foreach ($incomes as $income) {
                $taskCareer = new TaskIncome();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->idnature_of_income = $income;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }
        //save in community tables end
        return response()->json(['success' => 'dfd']);

    }

    public function deactivate(Request $request)
    {
        $id = $request['id'];
        $task = Task::find(intval($id));
        if ($task != null) {
            if ($task->status == 1) {
                $task->status = 0;
                $task->save();
            }

            return response()->json(['success' => 'Task deactivated!']);
        } else {
            return response()->json(['errors' => ['error' => 'Task invalid!']]);

        }
    }

    public function activate(Request $request)
    {
        $id = $request['id'];
        $task = Task::find(intval($id));
        if ($task != null) {
            if ($task->status == 0) {
                $task->status = 1;
                $task->save();
            }

            return response()->json(['success' => 'Task activated!']);
        } else {
            return response()->json(['errors' => ['error' => 'Task invalid!']]);

        }
    }

    public function createDefault()
    {

        $default = Task::with(['age', 'educations.education', 'religions.religion', 'incomes.income', 'careers.career', 'ethnicities.ethnicity'])->where('idoffice', Auth::user()->idoffice)->where('isDefault', 1)->first();
        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();
        $positions = Position::where('status', 1)->get();
        $taskTypes = TaskTypes::where('status', 1)->get();
        return view('task.default')->with(['taskTypes' => $taskTypes, 'positions' => $positions, 'default' => $default, 'title' => 'Default Budget', 'careers' => $careers, 'religions' => $religions, 'incomes' => $incomes, 'educations' => $educations, 'ethnicities' => $ethnicities]);

    }

    public function updateTask($memberId, $id)
    {
        $tasks = Task::where('idUser', $id)->where('status', 2)->get();
        $member = User::find($memberId);
        foreach ($tasks as $task) {
            if ($task->task_gender != 0) {
                if ($member->gender != $task->task_gender) {
                    continue;
                }
            }

            if ($task->task_job_sector != 0) {
                if ($member->member->is_government != $task->task_job_sector) {
                    continue;
                }
            }

            if ($task->religions != null) {
                if (!$task->religions->contains('idreligion', $member->member->idreligion)) {
                    continue;
                }
            }

            if ($task->ethnicities != null) {
                if (!$task->ethnicities->contains('idethnicity', $member->member->idethnicity)) {
                    continue;
                }
            }
//
//            if ($task->careers != null) {
//                if (!$task->careers->contains('idcareer', $member->member->idcareer)) {
//                    continue;
//                }
//            }

//            if ($task->incomes != null) {
//                if (!$task->careers->contains('idnature_of_income', $member->member->idnature_of_income)) {
//                    continue;
//                }
//            }
//
//            if ($task->educations != null) {
//                if (!$task->careers->contains('ideducational_qualification', $member->member->ideducational_qualification)) {
//                    continue;
//                }
//            }
//
//            if ($task->age != null) {
//                if ($task->age->comparison == 0 && $task->age->minAge != $member->user->age) {
//
//                    continue;
//                }
//                if ($task->age->comparison == 1 && $task->age->minAge <= $member->user->age) {
//
//                    continue;
//                }
//                if ($task->age->comparison == 2 && $task->age->minAge >= $member->user->age) {
//
//                    continue;
//                }
//                if ($task->age->comparison == 3 && ($task->age->minAge >= $member->user->age || $task->age->maxAge <= $member->user->age)) {
//
//                    continue;
//                }
//            }

//            $task->completed_amount += 1;
//            $task->save();
//            $this->isComplete($task->idtask);
//            break;
        }
    }

    public function isComplete($id)
    {
        $task = Task::find(intval($id));
        if ($task->target <= $task->completed_amount) {
            $task->status = 1;
            $task->save();
        }
    }
}
