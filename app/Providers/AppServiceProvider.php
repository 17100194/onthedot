<?php

namespace App\Providers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
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

            $groups = DB::table('groups')
                ->join('user_has_group', 'user_has_group.id_group', '=', 'groups.id')
                ->where('user_has_group.id_user', '=', Auth::id())
                ->orwhere('groups.id_creator', '=', Auth::id())->get();

//            $view->active = 'dashboard';
            $view->groupRequestPending = $groupRequestPending;
            $view->groupRequestRejected = $groupRequestRejected;
            $view->groupRequestAccepted = $groupRequestAccepted;
            $view->groups = $groups;
            $view->meetings = $meetings;
            $view->requests = $requests;
            $view->user = $user;
            $view->courses = $courses;
        });

//        view()->composer('layouts.app', function ($view) {
//
//            $requests = DB::table('meetings AS m')
//                ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
//                ->join('users AS u', 'u.id', '=', 'm.host')
//                ->where('um.userid', '=', Auth::id())
//                ->where('m.host', '!=', Auth::id())
//                ->where('m.status', '=', 'pending')->get();
//
//            $groupRequestAccepted = DB::table('user_has_group_request')
//                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
//                ->join('users', 'users.id', '=', 'user_has_group_request.id_receiver')
//                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'user_has_group_request.id as requestid')
//                ->where('id_sender', '=', Auth::id())
//                ->where('status', '=', 'accepted')
//                ->get();
//
//
//            $groupRequestRejected = DB::table('user_has_group_request')
//                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
//                ->join('users', 'users.id', '=', 'user_has_group_request.id_receiver')
//                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'user_has_group_request.id as requestid')
//                ->where('id_sender', '=', Auth::id())
//                ->where('status', '=', 'rejected')
//                ->get();
//
//
//            $groupRequestPending = DB::table('user_has_group_request')
//                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
//                ->join('users', 'users.id', '=', 'user_has_group_request.id_sender')
//                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'user_has_group_request.id as requestid')
//                ->where('id_receiver', '=', Auth::id())
//                ->where('status', '=', 'pending')
//                ->get();
//
//            $view->groupRequestPending = $groupRequestPending;
//            $view->groupRequestRejected = $groupRequestRejected;
//            $view->groupRequestAccepted = $groupRequestAccepted;
//            $view->requests = $requests;
//        });

        view()->composer('layouts.app', function ($view) {
            $view->requests = $this->getAllNotifications();
        });
    }

    public function getAllNotifications() {
        $requests = [];

        $meetingRequests = DB::table('meetings AS m')
            ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
            ->join('users AS u', 'u.id', '=', 'm.host')
            ->select('u.name AS username', 'm.time', 'u.campusid', 'm.id as meetingid', 'm.date')
            ->where('um.userid', '=', Auth::id())
            ->where('m.host', '!=', Auth::id())
            ->where('m.status', '=', 'pending')->get();

        foreach($meetingRequests as $row) {
            $html = '<a href="'.url('/notification?type=meeting-request&id='. strval($row->meetingid)).'"><strong>'.$row->username . ' ('.$row->campusid.')</strong>'. ' has requested to meet you at '.$row->time .' - ' . $row->date .'</a>';
            $requests[] = $html;
        }

        $groupRequestAccepted = DB::table('user_has_group_request')
            ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
            ->join('users', 'users.id', '=', 'user_has_group_request.id_receiver')
            ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'user_has_group_request.id as requestid')
            ->where('id_sender', '=', Auth::id())
            ->where('status', '=', 'accepted')
            ->get();

        foreach($groupRequestAccepted as $row) {
            $html = '<a href="javascript:void(0);"><strong>'.$row->username . ' ('.$row->campusid.')</strong>'. ' has accepted your request for the group <strong>'.$row->groupname.'</strong></a>';
            $requests[] = $html;
        }

        $groupRequestRejected = DB::table('user_has_group_request')
            ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
            ->join('users', 'users.id', '=', 'user_has_group_request.id_receiver')
            ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'user_has_group_request.id as requestid')
            ->where('id_sender', '=', Auth::id())
            ->where('status', '=', 'rejected')
            ->get();

        foreach($groupRequestRejected as $row) {
            $html = '<a href="javascript:void(0);"><strong>'.$row->username . ' ('.$row->campusid.')</strong>'. ' has rejected your request for the group <strong>'.$row->groupname.'</strong></a>';
            $requests[] = $html;
        }

        $groupRequestPending = DB::table('user_has_group_request')
            ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
            ->join('users', 'users.id', '=', 'user_has_group_request.id_sender')
            ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'user_has_group_request.id as requestid')
            ->where('id_receiver', '=', Auth::id())
            ->where('status', '=', 'pending')
            ->get();

        foreach($groupRequestPending as $row) {
            $html = '<a href="'.url('/notification?type=group-pending&id='. strval($row->requestid)).'"><strong>'.$row->username . ' ('.$row->campusid.')</strong>'. ' has requested you to join their group <strong>'.$row->groupname.'</strong></a>';
            $requests[] = $html;
        }

        return $requests;
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
