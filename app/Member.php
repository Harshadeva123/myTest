<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'idmember';

    public function user(){
        return $this->hasOne(User::class,'idUser');
    }
}
