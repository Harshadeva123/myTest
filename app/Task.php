<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'idtask';

    public function getNextNo(){
        $last = $this->whereHas('user', function($q){
            $q->where('idoffice', Auth::user()->idoffice);
        })->latest('idtask')->first();
        return  $last == null ? 1 : $last->task_no +1;
    }

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function assigned(){
        return $this->belongsTo(User::class,'assigned_by');
    }

    public function ethnicities(){
        return $this->hasMany(TaskEthnicity::class,'idtask');
    }

    public function careers(){
        return $this->hasMany(TaskCareer::class,'idtask');
    }

    public function incomes(){
        return $this->hasMany(TaskIncome::class,'idtask');
    }

     public function religions(){
        return $this->hasMany(TaskReligion::class,'idtask');
    }

    public function educations(){
        return $this->hasMany(TaskEducation::class,'idtask');
    }

    public function age(){
        return $this->hasOne(TaskAge::class,'idtask');
    }
}
