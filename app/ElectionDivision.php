<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElectionDivision extends Model
{
    protected $table = 'election_division';
    protected $primaryKey = 'idelection_division';

    public function district(){
        return $this->belongsTo(District::class,'iddistrict');
    }

    public function pollingBooths(){
        return $this->hasMany(PollingBooth::class,'idelection_division');
    }

    public function agents(){
        return $this->hasMany(Agent::class,'idelection_division');
    }
}
