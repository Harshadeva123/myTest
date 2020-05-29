<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'idevent';

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function analysis(){
        return $this->hasMany(Analysis::class,'idcategory');
    }
}
