<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest\PostStoreRequest;
use App\Http\Requests\PostRequest\PostUpdateRequest;
use App\Http\Controllers\Controller;
use App\Post;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = DB::table('posts')
            ->orderBy('created_at', 'desc')
            ->get();

        return $posts;
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

    public function store(PostStoreRequest $request)
    {

        $post = new Post;

        $post->name = $request['name'];
        $post->text = $request['text'];
        $post->img = 'storage/no-image.png';
        $post->user_id = Auth::user()->id;
        $post->save();

        if($request->file('images')) {

            $post->img = substr_replace(($request->file('images')[$request['main']]->store('public/posts')), 'storage', 0, 6);

            foreach($request->file('images') as $key => $image) {
                $img = new Image;
                $img->src = substr_replace(($image->store('public/posts')), 'storage', 0, 6);
                $img->post_id = $post->id;
                $img->save();
            }

        }

        $post->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {

        $user = Auth::user()->id;

        $username = User::find($post->user_id)->name;

        $images = Image::where('post_id', $post->id)->where('src', '!=', $post->img)->get();

        $comments = $post->comments()->orderBy('created_at', 'desc')->get();

        foreach($comments as $key => $comment) {
            $comments[$key] = $comment->id;
        }

        $data = [
            'username' => $username,
            'userid' => $post->user_id,
            'post' => $post,
            'images' => $images,
            'comments' => $comments,
            'user' => $user
        ];

        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $images = Image::where('post_id', $post->id)->get();

        $data = [
            'post' => $post,
            'files' => $images
        ];

        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
            $images = Image::where('post_id', $post->id)->get();

            foreach ($images as $image) {
                Storage::delete(substr_replace($image->src, 'public', 0, 7));
            }

            foreach ($images as $image) {
                $image->delete();
            }

            $data = $request->all();

            $post->name = $data['name'];
            $post->text = $data['text'];
            $post->img = 'storage/no-image.png';
            $post->save();

            if ($request->file('images')) {

                $post->img = substr_replace(($request->file('images')[$request['main']]->store('public/posts')), 'storage', 0, 6);

                foreach ($request->file('images') as $key => $image) {
                    $img = new Image;
                    $img->src = substr_replace(($image->store('public/posts')), 'storage', 0, 6);
                    $img->post_id = $post->id;
                    $img->save();
                }

            }

            $post->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if(Auth::user()->can('posts.delete', $post)) {

            foreach ($post->comments as $comment) {
                $comment->answers()->delete();
            }

            $post->comments()->delete();
            $images = Image::where('post_id', $post->id)->get();

            foreach ($images as $image) {
                Storage::delete(substr_replace($image->src, 'public', 0, 7));
            }
            foreach ($images as $image) {
                $image->delete();
            }

            $post->delete();

        } else return 0;
    }
}
