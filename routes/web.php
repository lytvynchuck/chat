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
    return redirect('home');
});
# Message
Route::resource('messages', 'MessageController');
# Room
Route::resource('room', 'RoomController');
Route::get('/room/{roomId}/bane/{userId}', 'RoomController@bane');
Route::get('/room/{roomId}/unbane/{userId}', 'RoomController@Unbane');
# Profile
Route::resource('profile', 'ProfileController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

