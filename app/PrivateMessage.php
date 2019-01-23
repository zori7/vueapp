<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
    protected $fillable = [
        'text',
        'target_user_id'
    ];

    public function user () {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function targetUser () {
        return $this->belongsTo('App\User', 'target_user_id');
    }
}
