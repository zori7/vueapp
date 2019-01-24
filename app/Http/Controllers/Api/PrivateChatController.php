<?php

namespace App\Http\Controllers\Api;

use App\Events\PrivateMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PrivateMessage as Message;
use Auth;
use DB;
use App\User;

class PrivateChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $messages = Message::where('user_id', $user->id)->orWhere('target_user_id', $user->id)->get();

        $chats = [];

        foreach($messages as $message) {
            if(!in_array($message->user_id, $chats) && $message->user_id != $user->id) $chats[] = $message->user_id;
            if(!in_array($message->target_user_id, $chats) && $message->target_user_id != $user->id) $chats[] = $message->target_user_id;
        }

        $users = [];

        $messages = [];

        foreach($chats as $key => $chat) {
            $users[$key] = User::find($chat);

            if(
                DB::table('private_messages')->where([
                    ['user_id', '=', $chat],
                    ['target_user_id', '=', $user->id],
                    ['message_read', '=', 0]
                ])->exists()
            ) {
                $messages[$key] = DB::table('private_messages')->where([
                    ['user_id', '=', $chat],
                    ['target_user_id', '=', $user->id],
                    ['message_read', '=', 0]
                ])->orderBy('created_at', 'desc')->first()->text;
            }
        }

        $data = [
            'chats' => $chats,
            'users' => $users,
            'messages' => $messages
        ];

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $currentUser = Auth::user();

        $message = $currentUser->privateMessages()->create([
            'text' => $request['text'],
            'target_user_id' => $request['target_user_id']
        ]);

        event(new PrivateMessage(array_merge($request->all(), ['id' => $message->id])));

        return $message->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $currentUser = Auth::user();
        $targetUser = User::find($id);

        $first = DB::table('private_messages')->where([
            ['user_id', '=', $currentUser->id],
            ['target_user_id', '=', $targetUser->id]
        ]);

        $messages = DB::table('private_messages')->where([
            ['user_id', '=', $targetUser->id],
            ['target_user_id', '=', $currentUser->id]
        ])->union($first)->orderBy('created_at')->get();

        foreach($messages as $message) {
            if($message->user_id == $currentUser->id) {
                $message->class = 'request';
            } else {
                $message->class = 'response';
            }
        }

        return $messages;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::find($id);

        $message->delete();
    }
}
