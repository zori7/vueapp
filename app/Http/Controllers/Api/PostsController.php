<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PostRequest\PostDestroyRequest;
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
use App\Services\ImageService;

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
        $posts = Post::with('main_image')->orderBy('created_at', 'desc')->get();

        foreach($posts as $post) {
            if($post->main_image) $post->src = $post->main_image->src;
        }

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
        $post->user_id = Auth::user()->id;
        $post->save();

        if($request->file('images')) {
            foreach($request->file('images') as $key => $image) {
                if($key == $request['main']) {
                    $post->main_id = ImageService::save($image, $post)->id;
                    $post->save();
                    continue;
                }
                ImageService::save($image, $post);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->load([
            'user',
            'images',
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);
        
        $user = Auth::user()->id;

        $username = $post->user->name;
        $images = $post->images;

        $comments = $post->comments;

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
        $data = [
            'post' => $post,
            'files' => $post->images
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
        $images = $post->images;

        foreach ($images as $image) {
            ImageService::delete($image->src, $image);
        }

        $data = $request->all();

        $post->name = $data['name'];
        $post->text = $data['text'];
        $post->save();

        if($request->file('images')) {
            foreach($request->file('images') as $key => $image) {
                if($key == $request['main']) {
                    $post->main_id = ImageService::save($image, $post)->id;
                    $post->save();
                    continue;
                }
                ImageService::save($image, $post);
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
    public function destroy(PostDestroyRequest $request, Post $post, Img $imgHelp)
    {
            foreach ($post->comments as $comment) {
                $comment->answers()->delete();
            }

            $post->comments()->delete();
            $images = Image::where('imageable_id', $post->id)->get();

            foreach ($images as $image) {
                $imgHelp->deleteImage($image->src);
            }
            foreach ($images as $image) {
                $image->delete();
            }

            $post->delete();
    }
}
