<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'post';
    protected $primaryKey = 'idPost';

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

}
