<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;
use App\Comment;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function image () {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function posts () {
        return $this->hasMany('App\Post');
    }

    public function comments () {
        return $this->hasMany('App\Comment');
    }

    public function answers () {
        return $this->hasMany('App\Answer');
    }

    public function privateMessages () {
        return $this->hasMany('App\PrivateMessage');
    }

    public function isAdmin () {

        $data = [
            'isAdmin' => false,
            'user' => $this
        ];

        foreach($this->roles as $role) {
            if($role->name == 'admin') $data['isAdmin'] = true;
        }

        return $data;
    }
}
