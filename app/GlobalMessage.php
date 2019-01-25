<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalMessage extends Model
{
    protected $fillable = [
        'text',
        'user_id',
        'created_at'
    ];
}
