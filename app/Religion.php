<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    protected $table = 'religion';
    protected $primaryKey = 'idreligion';

    public function agents(){
        return $this->hasMany(Agent::class,'idreligion');
    }
}
