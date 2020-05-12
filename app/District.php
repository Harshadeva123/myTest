<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'district';
    protected $primaryKey = 'iddistrict';

    public function electionDivisions(){
        return $this->hasMany(ElectionDivision::class,'iddistrict');
    }

    public function offices(){
        return $this->hasMany(Office::class,'iddistrict');
    }


}
