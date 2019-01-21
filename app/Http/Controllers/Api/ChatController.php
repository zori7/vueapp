<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class ChatController extends Controller
{
    public $message;

    public function sendMessage (Request $request) {

        DB::insert('insert into global_messages (text, created_at, user_id, user_name) values (?, ?, ?, ?)', [$request['message'], Carbon::now(), $request['userId'], $request['username']]);

        $now = Carbon::now()->toDateTimeString();

        event(new \App\Events\NewMessage([
            'user_id'  => $request['userId'],
            'text' => $request['message'],
            'created_at' => $now,
            'user_name' => $request['username']
        ]));

    }

    public function index () {

        $messages = DB::select("select * from global_messages");

        return $messages;

    }
}
