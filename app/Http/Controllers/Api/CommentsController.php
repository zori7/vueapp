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
        $comment->is_answer = false;

        $user->comments()->save($comment);

    }

    public function show ($id) {

        $comment = Comment::find($id);

        $username = User::find($comment->user_id)->name;

        return [
            'comment' => $comment,
            'username' => $username
        ];

    }
}
