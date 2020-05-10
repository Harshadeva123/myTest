<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'idtask';

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function assigned(){
        return $this->belongsTo(User::class,'assigned_by');
    }
}
