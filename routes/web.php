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
Route::get('register/verify/{token}', 'Auth\RegisterController@verify');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/dashboard', 'HomeController@index')->middleware('auth');
Route::post('/seeNotifications', 'HomeController@seeNotifications')->middleware('auth');
Route::get('/meetings', 'MeetingsController@index')->middleware('auth');
Route::get('/requested', 'MeetingsController@requested')->middleware('auth');
Route::get('/course/edit/{id}', 'CourseController@editCourse')->middleware('auth');
Route::post('/course/update', 'CourseController@updateCourse')->middleware('auth');
Route::get('/group/schedule/{id}', 'GroupController@getGroupTimetable')->middleware('auth');
Route::get('/search', 'MeetingsController@q');
Route::post('/meeting/schedule', 'MeetingsController@schedule')->middleware('auth');
Route::post('/meeting/scheduleGroup', 'MeetingsController@scheduleGroupMeeting')->middleware('auth');
Route::get('/admin/dashboard', 'AdminController@index')->middleware('auth','admin');
Route::post('/accept', 'MeetingsController@accept')->middleware('auth');
Route::post('/reject', 'MeetingsController@reject')->middleware('auth');
Route::post('/meetings/accept', 'MeetingsController@accept')->middleware('auth');
Route::post('/meetings/reject', 'MeetingsController@reject')->middleware('auth');
Route::get('/course/make', 'CourseController@makeform')->middleware('auth');
Route::get('/course/enroll', 'CourseController@enroll')->middleware('auth');
Route::post('/addcourse', 'CourseController@addcourse')->middleware('auth');
Route::get('/course/searchcourse', 'CourseController@searchcourse')->middleware('auth');
Route::post('/course/enrollcourse', 'CourseController@enrollcourse')->middleware('auth');
Route::get('/group/make', 'GroupController@createForm')->middleware('auth');
Route::get('/group/adduser','GroupController@searchuser')->middleware('auth');
Route::post('/makegroup', 'GroupController@makegroup')->middleware('auth');
Route::post('/group/acceptRequest', 'GroupController@acceptRequest')->middleware('auth');
Route::get('/course/all', 'CourseController@all')->middleware('auth');
Route::get('/meetings/requests', 'MeetingsController@requests')->middleware('auth');
Route::post('/group/rejectRequest', 'GroupController@rejectRequest')->middleware('auth');
Route::get('/notification', 'NotificationController@viewNotification')->middleware('auth');
Route::get('/timetable', 'HomeController@Timetable')->middleware('auth');
Route::post('/course/dropcourse', 'CourseController@dropCourse')->middleware('auth');
Route::post('dropcourse', 'CourseController@dropCourse')->middleware('auth');
Route::get('/group/all', 'GroupController@all')->middleware('auth');
Route::post('/cancelMeeting', 'MeetingsController@cancelMeeting')->middleware('auth');
Route::post('/group/leaveGroup', 'GroupController@leaveGroup')->middleware('auth');
Route::post('/group/removeMember', 'GroupController@removeMember')->middleware('auth');
Route::post('/group/sendGroupRequest', 'GroupController@sendGroupRequest')->middleware('auth');
Route::get('/group/groupdetails', 'GroupController@groupDetails')->middleware('auth');
Route::get('/group/showGroupMembers', 'GroupController@showGroupMembers')->middleware('auth');
Route::get('/meetingdetails', 'MeetingsController@meetingDetails')->middleware('auth');
Route::get('/course/coursedetails', 'CourseController@courseDetails')->middleware('auth');
Route::get('/showtimetable', 'HomeController@showTimetable')->middleware('auth');
Route::get('/meetings/requestdetails', 'MeetingsController@requestDetails')->middleware('auth');
Route::get('/sentrequestdetails', 'MeetingsController@sentRequestDetails')->middleware('auth');
Route::get('/meeting/schedule','MeetingsController@scheduleMeeting')->middleware('auth');
Route::get('/meeting/searchuser','MeetingsController@searchUser')->middleware('auth');
Route::get('/meeting/gettimetable','MeetingsController@getTimetable');
Route::get('/getnotifications', 'NotificationController@getNotifications')->middleware('auth');
Route::post('/seenotifications','NotificationController@seeNotifications')->middleware('auth');
Route::get('/checknotifications', 'NotificationController@checkNotifications')->middleware('auth');
Route::get('/checkrequests', 'HomeController@checkRequests')->middleware('auth');
Route::get('/group/requests','GroupController@requests')->middleware('auth');
Route::get('/group/requestdetails', 'GroupController@requestDetails')->middleware('auth');
Route::get('/settings','HomeController@Settings')->middleware('auth');
Route::post('/updatePassword','HomeController@updatePassword')->middleware('auth');
Route::post('/deleteAccount','HomeController@deleteAccount')->middleware('auth');
Route::post('/addNewUser','AdminController@addNewUser')->middleware('auth','admin');
Route::post('/sendcontactform','HomeController@SendContactForm');