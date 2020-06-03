<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'village';
    protected $primaryKey = 'idvillage';

    public function gramasewaDivision(){
        return $this->belongsTo(GramasewaDivision::class,'idgramasewa_division');
    }
    public function agents(){
        return $this->hasMany(Agent::class,'idvillage');
    }
    public function member(){
        return $this->hasMany(Member::class,'idvillage');
    }

    public function events(){
        return $this->hasMany(Event::class,'idvillage');
    }

    public function analysis()
    {
        return $this->belongsTo(Analysis::class, 'idvillage');
    }
}
