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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('conversation','ConversationController');
Route::resource('message','MessageController');

Route::get('chat', 'ChatController@chat');
Route::post('sendMessage', 'ChatController@sendMessage');

//group message
Route::get('group_chat', 'ChatController@group_chat');
Route::post('group_chat_store', 'ChatController@group_chat_store');