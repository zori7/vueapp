<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Post;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index () {
        return view('users');
    }

    public function getUsers() {

        $users = User::all();

        foreach($users as $user) {
            $posts[$user->id] = $user->posts->count();
        }

        return [$users, $posts];
    }

    public function registerFromTable(Request $request)
    {
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
    }

    public function delete(Request $request) {

        $user = User::find($request->id);

        $posts = Post::where('user_id', $user->id);

        $posts->delete();

        $user->delete();
    }

    public function isAdmin () {
        $user = Auth::user();
        $data = [
            'isAdmin' => false,
            'user' => $user
        ];

        foreach($user->roles as $role) {
            if($role->name == 'admin') $data['isAdmin'] = true;
        }
        return $data;
    }

    public function showEdit($id) {

        $user = User::find($id);

        $data = [
            'user' => $user
        ];
        return $data;
    }

    public function edit (Request $request, $id) {
        $data = $request->all();

        $user = User::find($id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        $user->save();

    }

    public function showUser ($id) {
        $user = User::find($id);
        $postsCount = $user->posts->count();
        $user->password = "";
        $data = ['user' => $user, 'postsCount' => $postsCount, 'isAdmin' => "No"];

        foreach($user->roles as $role) {
            if($role->name == 'admin') $data['isAdmin'] = "Yes";
        }

        return $data;
    }
}
