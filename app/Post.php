<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'name',
        'text',
        'main_id',
        'user_id'
    ];

    public function user () {
        return $this->belongsTo('App\User');
    }

    public function images () {
        return $this->morphMany('App\Image', 'imageable');
    }

    public function main_image () {
        return $this->belongsTo('App\Image', 'main_id');
    }

    public function comments () {
        return $this->hasMany('App\Comment');
    }

}
