<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $table = 'agent';
    protected $primaryKey = 'idagent';

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
}
