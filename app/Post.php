<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'post';
    protected $primaryKey = 'idPost';

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function attachments(){
        return $this->hasMany(PostAttachment::class,'idPost');
    }

    public function responses(){
        return $this->hasMany(PostResponse::class,'idPost');
    }

    public function getSize(){
        $attachment =  $this->attachments()->sum('size');
        $responses = $this->responses()->sum('size');
        return $attachment + $responses;
    }

    public function nextPostNo($office){
        $last = $this->whereHas('user', function (Builder $query) use($office) {
            $query->where('idoffice',$office);
        })->latest('idPost')->first();
        return  $last == null ? 1 : $last->post_no + 1;
    }
}
