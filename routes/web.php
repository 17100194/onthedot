<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello/{param}', 'Hello@index');

Route::get('login', function() {

})->name('login');
Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/home', 'HomeController@index');
Route::get('/meetings', 'MeetingsController@index');
Route::get('/search', 'MeetingsController@q');
Route::get('/schedule', 'MeetingsController@schedule');