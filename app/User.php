<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    protected $table = 'usermaster';
    protected $primaryKey = 'idUser';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fName', 'email', 'password', 'iduser_role', 'idbranch'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * return user role meta data
     */
    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'iduser_role');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'idoffice');
    }

    public function userTitle()
    {
        return $this->belongsTo(UserTitle::class, 'iduser_title');
    }

    public function taskAgent()
    {
        return $this->hasMany(Task::class, 'idUser');
    }

    public function taskAssigned()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    public function post()
    {
        return $this->hasMany(Post::class, 'idUser');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'idUser');
    }

    public function agent()
    {
        return $this->hasOne(Agent::class, 'idUser');
    }

    public function officeAdmin()
    {
        return $this->hasOne(OfficeAdmin::class, 'idUser');
    }

    public function officeStaff()
    {
        return $this->hasOne(OfficeStaff::class, 'idUser');
    }

    public function responses()
    {
        return $this->hasMany(PostResponse::class, 'idUser');
    }
}
