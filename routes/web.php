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

Route::prefix('api')->group(function () {

//  Resources

    Route::resource('posts', 'Api\PostsController');
    Route::resource('users', 'Api\UsersController');
    Route::resource('pm', 'Api\PrivateChatController');
    Route::resource('comments', 'Api\CommentsController');
    Route::resource('answers', 'Api\AnswersController');

//  Private messages
    Route::post('readmessage/{message}', 'Api\UsersController@readMessage');
    Route::post('readall/{user}', 'Api\UsersController@readAllMessages');

//  Admin
    Route::get('isadmin', 'Api\UsersController@isAdmin');
    Route::post('admin/make/{user}', 'Api\UsersController@makeAdmin');
    Route::post('admin/delete/{user}', 'Api\UsersController@deleteAdmin');

//  Global chat
    Route::post('global', 'Api\GlobalChatController@store');
    Route::get('global', 'Api\GlobalChatController@index');

//  Others
    Route::post('deleteavatar/{user}', 'Api\UsersController@deleteAvatar');
});
