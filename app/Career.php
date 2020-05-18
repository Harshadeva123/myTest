<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $table = 'career';
    protected $primaryKey = 'idcareer';

    public function agents(){
        return $this->hasMany(Agent::class,'idcareer');
    }

    public function taskCareer(){
        return $this->hasMany(TaskCareer::class,'idcareer');
    }
}
