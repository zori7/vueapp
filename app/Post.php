<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'name',
        'text',
        'img',
        'user_id'
    ];

    public function user () {
        return $this->belongsTo('App\User');
    }

    public function images () {
        return $this->hasMany('App\Image');
    }

    public function comments () {
        return $this->hasMany('App\Comment');
    }

}
