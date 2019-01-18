<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Answer;
use App\User;
use Auth;
use App\Comment;

class AnswersController extends Controller
{
    public function store (Request $request) {

        $user = Auth::user();

        $answer = new Answer;

        $answer->text = $request['text'];
        $answer->comment_id = $request['comment_id'];

        $user->answers()->save($answer);

    }

    public function show ($id) {

        $answer = Answer::find($id);

        $user = User::find($answer->user_id);

        return [
            'answer' => $answer,
            'user' => $user
        ];

    }

    public function update (Request $request, $id) {
        $answer = Answer::find($id);

        $answer->text = $request['text'];

        $answer->save();

    }

    public function destroy ($id) {

        $answer = Answer::find($id);

        $answer->delete();

    }
}
