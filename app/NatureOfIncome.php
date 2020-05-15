<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NatureOfIncome extends Model
{
    protected $table = 'nature_of_income';
    protected $primaryKey = 'idnature_of_income';

    public function agents(){
        return $this->hasMany(Agent::class,'idnature_of_income');
    }

}
