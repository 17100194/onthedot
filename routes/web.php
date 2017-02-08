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
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('login', function() {

})->name('login');
Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/home', 'HomeController@index');
Route::get('/meetings', 'MeetingsController@index');
Route::get('/search', 'MeetingsController@q');
Route::post('/schedule', 'MeetingsController@schedule');
Route::post('/accept', 'MeetingsController@accept');
Route::post('/reject', 'MeetingsController@reject');
Route::get('/course/make', 'CourseController@makeform');
Route::get('/course/enroll', 'CourseController@enroll');
Route::post('/addcourse', 'CourseController@addcourse');
Route::post('/course/searchcourse', 'CourseController@searchcourse');
Route::post('/course/enrollcourse', 'CourseController@enrollcourse');
Route::get('/group/make', 'GroupController@createForm');
Route::get('/group/adduser','GroupController@searchuser');
Route::post('/group/makegroup', 'GroupController@makegroup');
Route::post('/acceptRequest', 'GroupController@acceptRequest');
Route::post('/rejectRequest', 'GroupController@rejectRequest');
Route::get('/course/all', 'CourseController@all');
Route::get('/meetings/requests', 'MeetingsController@requests');
Route::post('/rejectRequest', 'GroupController@rejectRequest');
Route::post('/rejectRequest', 'GroupController@rejectRequest');
Route::get('/notification', 'NotificationController@viewNotification');
