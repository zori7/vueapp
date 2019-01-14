<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use App\User;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index () {
        return view('posts');
    }

    public function getPosts () {

        $posts = DB::table('posts')
            ->orderBy('created_at', 'desc')
            ->get();

        return $posts;
    }

    public function getUserName (Request $request) {

        return Post::find($request['id'])->user->name;

    }

    public function create (Request $request) {

        $post = new Post;

        $this->validate($request, [
            'name' => 'required',
            'text' => 'required'
        ]);

        $post->name = $request['name'];
        $post->text = $request['text'];
        if($request['img']) {
            $post->img = substr_replace(($request->file('img')->store('public/posts')), 'storage', 0, 6);
        } else {
            $post->img = 'storage/no-image.png';
        }

        $post->user_id = Auth::user()->id;
        $post->save();

    }

    public function delete(Request $request) {
        $post = Post::find($request->id);

        if($post->img != "storage/no-image.png") {
            Storage::delete(substr_replace($post->img, 'public', 0, 7));
        }

        $post->delete();
    }

    public function showEdit ($id) {
        $post = Post::find($id);

        return $post;
    }

    public function edit (Request $request, $id) {
        $data = $request->all();

        $post = Post::find($id);
        $post->name = $data['name'];
        $post->text = $data['text'];

        if($request['img']) {

            if($post->img != "storage/no-image.png") {
                Storage::delete(substr_replace($post->img, 'public', 0, 7));
            }

            $post->img = substr_replace(($request->file('img')->store('public/posts')), 'storage', 0, 6);
        }

        $post->save();

    }

    public function showPost($id) {
        $post = Post::find($id);
        $username = User::find($post->user_id)->name;
        $data = ['username' => $username, 'post' => $post];
        return $data;
    }
}
