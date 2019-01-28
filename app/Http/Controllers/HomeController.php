<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $user = Auth::user();

        $fromUsers = [];

        $messages = DB::table('private_messages')->where([
            ['target_user_id', '=', $user->id],
            ['message_read', '=', 0]
        ])->get();

        foreach($messages as $message) {
            if(!in_array($message->user_id, $fromUsers)) {
                $fromUsers[] = $message->user_id;
            }
        }
        return view('home', [
            'messagesCount' => count($fromUsers),
            'avatar' => $user->image ? $user->image->src : false
        ]);
    }
}
