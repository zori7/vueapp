<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;
use App\User;
use Auth;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $user = Auth::user();

        $comment = new Comment;

        $comment->text = $request['text'];
        $comment->post_id = $request['post_id'];

        $user->comments()->save($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
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
    public function update(Request $request, Comment $comment)
    {
        if(Auth::user()->can('comments.update', $comment)) {
            $comment->text = $request['text'];

            $comment->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if(Auth::user()->can('comments.delete', $comment)) {
            $comment->answers()->delete();
            $comment->delete();
        }
    }
}
