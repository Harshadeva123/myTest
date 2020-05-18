<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostResponse extends Model
{
    protected $table = 'post_response';
    protected $primaryKey = 'idpost_response';
    protected $appends = array('full_path');

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function getFullPathAttribute()
    {
        if ($this->response_type == 2) {
            return 'storage/' . $this->post->user->office->random . '/comments/images/' . $this->attachment;
        } else if ($this->response_type == 3) {
            return 'storage/' . $this->post->user->office->random . '/comments/videos/' . $this->attachment;
        } else if ($this->response_type == 4) {
            return 'storage/' . $this->post->user->office->random . '/comments/audios/' . $this->attachment;
        } else {
            return '';
        }
    }
}
