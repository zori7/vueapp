<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Post;
use DB;

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
    public function store(Request $request)
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
    public function show($id)
    {

        $user = User::find($id);
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
    public function edit($id)
    {

        $user = User::find($id);

        $data = [
            'user' => $user
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
    public function update(Request $request, $id)
    {

        $data = $request->all();

        $user = User::find($id);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if($request['password']) {
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
    public function destroy($id)
    {

        $user = User::find($id);

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

    public function makeAdmin ($id) {

        DB::table('role_user')->insert([
            'user_id' => $id,
            'role_id' => 1
        ]);

    }

    public function deleteAdmin ($id) {

        DB::table('role_user')->where('user_id', $id)->delete();

    }
}
