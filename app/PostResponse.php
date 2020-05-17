<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostResponse extends Model
{
    protected $table = 'post_response';
    protected $primaryKey = 'idpost_response';

    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

}
