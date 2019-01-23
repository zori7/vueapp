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

    Route::resource('posts', 'Api\PostsController');

    Route::resource('users', 'Api\UsersController');

    Route::resource('pm', 'Api\PrivateChatController');

    Route::get('/isadmin', 'Api\UsersController@isAdmin');

    Route::post('admin/make/{id}', 'Api\UsersController@makeAdmin');

    Route::post('admin/delete/{id}', 'Api\UsersController@deleteAdmin');

    Route::get('/comments/{id}', 'Api\CommentsController@show');

    Route::post('/comments', 'Api\CommentsController@store');

    Route::post('/comments/edit/{id}', 'Api\CommentsController@update');

    Route::delete('/comments/{id}', 'Api\CommentsController@destroy');

    Route::get('/answers/{id}', 'Api\AnswersController@show');

    Route::post('/answers', 'Api\AnswersController@store');

    Route::post('/answers/edit/{id}', 'Api\AnswersController@update');

    Route::delete('/answers/{id}', 'Api\AnswersController@destroy');

});
