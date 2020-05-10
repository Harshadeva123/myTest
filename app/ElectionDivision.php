<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElectionDivision extends Model
{
    protected $table = 'election_division';
    protected $primaryKey = 'idelection_division';

    public function District(){
        return $this->belongsTo(District::class,'iddistrict');
    }
}
