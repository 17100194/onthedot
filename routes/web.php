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
Route::post('/scheduleGroup', 'MeetingsController@scheduleGroupMeeting');
Route::get('/admin', 'UserController@adminForm');
Route::post('/adminlogin', 'UserController@adminLogin');
Route::post('/accept', 'MeetingsController@accept');
Route::post('/reject', 'MeetingsController@reject');
Route::post('/meetings/accept', 'MeetingsController@accept');
Route::post('/meetings/reject', 'MeetingsController@reject');
Route::get('/course/make', 'CourseController@makeform');
Route::get('/course/enroll', 'CourseController@enroll');
Route::post('/addcourse', 'CourseController@addcourse');
Route::post('/course/searchcourse', 'CourseController@searchcourse');
Route::post('/course/enrollcourse', 'CourseController@enrollcourse');
Route::get('/group/make', 'GroupController@createForm');
Route::get('/group/adduser','GroupController@searchuser');
Route::post('/group/makegroup', 'GroupController@makegroup');
Route::post('/acceptRequest', 'GroupController@acceptRequest');
Route::get('/course/all', 'CourseController@all');
Route::get('/meetings/requests', 'MeetingsController@requests');
Route::post('/rejectRequest', 'GroupController@rejectRequest');
Route::get('/notification', 'NotificationController@viewNotification');
Route::get('/timetable', 'HomeController@Timetable');
Route::post('/course/dropcourse', 'CourseController@dropCourse');
Route::get('/group/all', 'GroupController@all');
Route::post('/cancelMeeting', 'MeetingsController@cancelMeeting');
Route::post('/group/leaveGroup', 'GroupController@leaveGroup');
Route::post('/group/removeMember', 'GroupController@removeMember');
ROute::post('/group/sendGroupRequest', 'GroupController@sendGroupRequest');