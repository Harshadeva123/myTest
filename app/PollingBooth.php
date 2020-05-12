<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollingBooth extends Model
{
    protected $table = 'polling_booth';
    protected $primaryKey = 'idpolling_booth';

    public function electionDivision(){
        return $this->belongsTo(ElectionDivision::class,'idelection_division');
    }
    public function gramasewaDivisions(){
        return $this->hasMany(GramasewaDivision::class,'idpolling_booth');
    }
    public function agents(){
        return $this->hasMany(Agent::class,'idpolling_booth');
    }
}
