<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'text'
    ];

    public function user () {
        return $this->belongsTo('App\User');
    }

    public function post () {
        return $this->belongsTo('App\Post');
    }

    public function answers () {
        return $this->hasMany('App\Answer');
    }

}
