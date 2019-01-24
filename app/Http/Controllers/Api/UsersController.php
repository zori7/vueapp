<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest\UserStoreRequest;
use App\Http\Requests\UserRequest\UserUpdateRequest;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Post;
use App\Comment;
use App\Answer;
use DB;
use App\Image;
use Illuminate\Support\Facades\Storage;
use Gate;
use App\PrivateMessage as Message;

class UsersController extends Controller
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

        $users = User::all();

        foreach($users as $user) {
            $posts[$user->id] = $user->posts->count();
        }

        return [$users, $posts];

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
    public function store(UserStoreRequest $request)
    {
            User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $postsCount = $user->posts->count();
        $user->password = "";
        $data = ['user' => $user, 'postsCount' => $postsCount, 'isAdmin' => "No"];

        foreach($user->roles as $role) {
            if($role->name == 'admin') $data['isAdmin'] = "Yes";
        }

        return $data;


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if(Gate::allows('all')) {
            $data = [
                'user' => $user
            ];
            return $data;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
            $data = $request->all();

            $user->name = $data['name'];
            $user->email = $data['email'];
            if ($request['password']) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(Gate::allows('all')) {

            $posts = Post::where('user_id', $user->id)->get();

            foreach ($posts as $post) {
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
            }

            $comments = Comment::where('user_id', $user->id)->get();

            foreach ($comments as $comment) {
                $comment->answers()->delete();
                $comment->delete();
            }

            $answers = Answer::where('user_id', $user->id)->get();

            foreach ($answers as $answer) {
                $answer->delete();
            }

            $user->delete();
        }
    }

    public function isAdmin () {
        return Auth::user()->isAdmin();
    }

    public function makeAdmin (User $user) {

        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => 1
        ]);

    }

    public function deleteAdmin (User $user) {
        DB::table('role_user')->where('user_id', $user->id)->delete();
    }

    public function readMessage (Message $message) {
        $message->message_read = 1;
        $message->save();
    }

    public function readAllMessages (User $user) {
        if(
            DB::table('private_messages')->where([
                ['user_id', '=', $user->id],
                ['target_user_id', '=', Auth::user()->id],
                ['message_read', '=', 0]
        ])->exists()
        ) {
            DB::table('private_messages')->where([
                ['user_id', '=', $user->id],
                ['target_user_id', '=', Auth::user()->id],
                ['message_read', '=', 0]
            ])->update(['message_read' => 1]);
        }
    }
}
