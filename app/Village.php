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
}
