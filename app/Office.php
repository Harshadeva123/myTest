<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'office';
    protected $primaryKey = 'idoffice';

    public function users(){
        return $this->hasMany(User::class,'idoffice');
    }

    public function payments(){
        return $this->hasMany(Payment::class,'idoffice');
    }

    public function district(){
        return $this->belongsTo(District::class,'iddistrict');
    }

    public function posts(){
        return $this->belongsTo(Post::class,'idoffice');
    }

    public function smaLimit(){
        return $this->hasOne(SmsLimit::class,'idoffice');
    }

    public function officeSetting(){
        return $this->hasOne(OfficeSetting::class,'idoffice');
    }

}
