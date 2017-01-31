<?php

namespace App\Providers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.sidemenu', function ($view) {
            $meetings = DB::table('user_has_meeting AS u1')
                ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
                ->join('users', 'u2.userid', '=', 'users.id')
                ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
                ->where('u1.userid', '=', Auth::id())
                ->where('u2.userid', '!=', Auth::id())->get();

            $requests = DB::table('meetings AS m')
                ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
                ->join('users AS u', 'u.id', '=', 'm.host')
                ->where('um.userid', '=', Auth::id())
                ->where('m.host', '!=', Auth::id())
                ->where('m.status', '=', 'pending')->get();
            $user = DB::table('users')->where('users.id', '=', Auth::id())->get();
            $user = $user[0];
            $courses = DB::table('user_has_course')
                ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
                ->where('user_has_course.userid', '=', Auth::id())->get();

            $groupRequestAccepted = DB::table('user_has_group_request')
                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
                ->join('users', 'users.id', '=', 'user_has_group_request.id_receiver')
                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'user_has_group_request.id as requestid')
                ->where('id_sender', '=', Auth::id())
                ->where('status', '=', 'accepted')
                ->get();


            $groupRequestRejected = DB::table('user_has_group_request')
                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
                ->join('users', 'users.id', '=', 'user_has_group_request.id_receiver')
                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'user_has_group_request.id as requestid')
                ->where('id_sender', '=', Auth::id())
                ->where('status', '=', 'rejected')
                ->get();


            $groupRequestPending = DB::table('user_has_group_request')
                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
                ->join('users', 'users.id', '=', 'user_has_group_request.id_sender')
                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'user_has_group_request.id as requestid')
                ->where('id_receiver', '=', Auth::id())
                ->where('status', '=', 'pending')
                ->get();


            $view->groupRequestPending = $groupRequestPending;
            $view->groupRequestRejected = $groupRequestRejected;
            $view->groupRequestAccepted = $groupRequestAccepted;

            $view->meetings = $meetings;
            $view->requests = $requests;
            $view->user = $user;
            $view->courses = $courses;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
