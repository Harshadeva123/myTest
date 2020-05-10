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
}
