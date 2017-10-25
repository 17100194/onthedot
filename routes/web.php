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
})->middleware('minify');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('login', function() {

})->name('login');
Auth::routes();
Route::get('register/verify/{token}', 'Auth\RegisterController@verify');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/dashboard', 'HomeController@index')->middleware('auth','minify');
Route::post('/seeNotifications', 'HomeController@seeNotifications')->middleware('auth','minify');
Route::get('/meetings', 'MeetingsController@index')->middleware('auth','minify');
Route::get('/requested', 'MeetingsController@requested')->middleware('auth','minify');
Route::get('/group/schedule/{id}', 'GroupController@getGroupTimetable')->middleware('auth','minify');
Route::get('/search', 'MeetingsController@q')->middleware('minify');
Route::post('/meeting/schedule', 'MeetingsController@schedule')->middleware('auth','minify');
Route::post('/meeting/scheduleGroup', 'MeetingsController@scheduleGroupMeeting')->middleware('auth','minify');
Route::get('/admin/dashboard', 'AdminController@index')->middleware('auth','admin','minify');
Route::post('/accept', 'MeetingsController@accept')->middleware('auth','minify');
Route::post('/reject', 'MeetingsController@reject')->middleware('auth','minify');
Route::post('/meetings/accept', 'MeetingsController@accept')->middleware('auth','minify');
Route::post('/meetings/reject', 'MeetingsController@reject')->middleware('auth','minify');
Route::get('/group/make', 'GroupController@createForm')->middleware('auth','minify');
Route::get('/group/adduser','GroupController@searchuser')->middleware('auth','minify');
Route::post('/makegroup', 'GroupController@makegroup')->middleware('auth','minify');
Route::post('/group/acceptRequest', 'GroupController@acceptRequest')->middleware('auth','minify');
Route::get('/meetings/requests', 'MeetingsController@requests')->middleware('auth','minify');
Route::post('/group/rejectRequest', 'GroupController@rejectRequest')->middleware('auth','minify');
Route::get('/notification', 'NotificationController@viewNotification')->middleware('auth','minify');
Route::get('/timetable', 'HomeController@Timetable')->middleware('auth','minify');
Route::get('/group/all', 'GroupController@all')->middleware('auth','minify');
Route::post('/cancelMeeting', 'MeetingsController@cancelMeeting')->middleware('auth','minify');
Route::post('/group/leaveGroup', 'GroupController@leaveGroup')->middleware('auth','minify');
Route::post('/group/removeMember', 'GroupController@removeMember')->middleware('auth','minify');
Route::post('/group/sendGroupRequest', 'GroupController@sendGroupRequest')->middleware('auth','minify');
Route::get('/group/groupdetails', 'GroupController@groupDetails')->middleware('auth','minify');
Route::get('/group/showGroupMembers', 'GroupController@showGroupMembers')->middleware('auth','minify');
Route::get('/meetingdetails', 'MeetingsController@meetingDetails')->middleware('auth','minify');
Route::get('/showtimetable', 'HomeController@showTimetable')->middleware('auth','minify');
Route::get('/meetings/requestdetails', 'MeetingsController@requestDetails')->middleware('auth','minify');
Route::get('/sentrequestdetails', 'MeetingsController@sentRequestDetails')->middleware('auth','minify');
Route::get('/meeting/schedule','MeetingsController@scheduleMeeting')->middleware('auth','minify');
Route::get('/meeting/searchuser','MeetingsController@searchUser')->middleware('auth','minify');
Route::get('/meeting/gettimetable','MeetingsController@getTimetable')->middleware('minify');
Route::get('/getnotifications', 'NotificationController@getNotifications')->middleware('auth','minify');
Route::post('/seenotifications','NotificationController@seeNotifications')->middleware('auth','minify');
Route::get('/checknotifications', 'NotificationController@checkNotifications')->middleware('auth','minify');
Route::get('/checkrequests', 'HomeController@checkRequests')->middleware('auth','minify');
Route::get('/group/requests','GroupController@requests')->middleware('auth','minify');
Route::get('/group/requestdetails', 'GroupController@requestDetails')->middleware('auth','minify');
Route::get('/settings','HomeController@Settings')->middleware('auth','minify');
Route::post('/updatePassword','HomeController@updatePassword')->middleware('auth','minify');
Route::post('/deleteAccount','HomeController@deleteAccount')->middleware('auth','minify');
Route::post('/addNewUser','AdminController@addNewUser')->middleware('auth','admin','minify');
Route::get('/sendcontactform','HomeController@SendContactForm')->middleware('minify');