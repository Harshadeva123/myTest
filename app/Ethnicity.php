<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ethnicity extends Model
{
    protected $table = 'ethnicity';
    protected $primaryKey = 'idethnicity';

    public function agents(){
        return $this->hasMany(Agent::class,'idethnicity');
    }
}
