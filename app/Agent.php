<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $table = 'agent';
    protected $primaryKey = 'idagent';


    public function numberOfMembers(){
        return MemberAgent::where('idagent',$this->idagent)->where('status',1)->count();
    }

    public function user(){
        return $this->hasOne(User::class,'idUser');
    }

    public function electionDivision(){
        return $this->belongsTo(ElectionDivision::class,'idelection_division');
    }

    public function pollingBooth(){
        return $this->belongsTo(PollingBooth::class,'idpolling_booth');
    }

    public function gramasewaDivision(){
        return $this->belongsTo(GramasewaDivision::class,'idgramasewa_division');
    }

    public function village(){
        return $this->belongsTo(Village::class,'idvillage');
    }

    public function religion(){
        return $this->belongsTo(Religion::class,'idreligion');
    }

    public function career(){
        return $this->belongsTo(Career::class,'idcareer');
    }

    public function ethnicity(){
        return $this->belongsTo(Ethnicity::class,'idethnicity');
    }

    public function natureOfIncome(){
        return $this->belongsTo(NatureOfIncome::class,'idnature_of_income');
    }

    public function educationalQualification(){
        return $this->belongsTo(EducationalQualification::class,'ideducational_qualification');
    }
}
