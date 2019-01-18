<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;
use App\User;
use Auth;

class CommentsController extends Controller
{
    public function store (Request $request) {

        $user = Auth::user();

        $comment = new Comment;

        $comment->text = $request['text'];
        $comment->post_id = $request['post_id'];

        $user->comments()->save($comment);

    }

    public function show ($id) {

        $comment = Comment::find($id);

        $user = User::find($comment->user_id);

        $answers = $comment->answers()->get();

        foreach($answers as $key => $answer) {
            $answers[$key] = $answer->id;
        }

        return [
            'comment' => $comment,
            'user' => $user,
            'answers' => $answers
        ];

    }

    public function update (Request $request, $id) {
        $comment = Comment::find($id);

        $comment->text = $request['text'];

        $comment->save();

    }

    public function destroy ($id) {

        $comment = Comment::find($id);

        $comment->delete();

    }
}
