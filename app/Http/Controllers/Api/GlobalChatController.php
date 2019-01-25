<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use App\Events\GlobalMessage;
use Carbon\Carbon;
use App\GlobalMessage as Message;

class GlobalChatController extends Controller
{
    public function index () {
        $messages = DB::table('global_messages')->orderBy('created_at', 'asc')->get();
        $dataMessages = [];
        foreach($messages as $key => $message) {
            $dataMessages[$key] = new Message;
            $dataMessages[$key]->text = $message->text;
            $dataMessages[$key]->user = User::find($message->user_id);

            $time = Carbon::parse($message->created_at)->toTimeString();
            $dataMessages[$key]->created = $time;
        }

        return $dataMessages;
    }

    public function store (Request $request) {

        $user = User::find($request['user_id']);

        DB::table('global_messages')->insert([
            'user_id' => $user->id,
            'text' => $request['text'],
            'created_at' => Carbon::now()
        ]);

        $data = [
            'text' => $request['text'],
            'user' => $user,
            'created' => Carbon::now()->toTimeString()
        ];

        event(new GlobalMessage($data));
    }
}
