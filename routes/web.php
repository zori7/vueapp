<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index');

Route::get('/posts', 'PostsController@index')->name('posts');

Route::get('/users', 'UsersController@index')->name('users');

Route::get('/edit/user/{id}', 'UsersController@showEdit')->name('show_edit_user');
Route::post('/edit/user/{id}', 'UsersController@edit')->name('edit_user');


Route::post('/create/post', 'PostsController@create')->name('create_post');

Route::get('/edit/post/{id}', 'PostsController@showEdit')->name('show_edit_post');
Route::post('/edit/post/{id}', 'PostsController@edit')->name('edit_post');

Route::get('/post/{id}', 'PostsController@showPost')->name('show_post');

Route::get('/user/{id}', 'UsersController@showUser')->name('show_user');







Route::get('/users/getusers', 'UsersController@getUsers');

Route::get('/posts/getposts', 'PostsController@getPosts');
Route::get('posts/getusername/{id}', 'PostsController@getUserName');
Route::post('/deletepost', 'PostsController@delete');

Route::get('/isadmin', 'UsersController@isAdmin');

Route::post('/deleteuser', 'UsersController@delete');

Route::post('/registerfromtable', 'UsersController@registerFromTable');