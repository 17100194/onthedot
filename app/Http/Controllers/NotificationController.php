<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class NotificationController extends Controller
{
    public function viewNotification(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');
        $req = [];
        if ($type == "group-pending") {

            $req = DB::table('user_has_group_request')
                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
                ->join('users', 'users.id', '=', 'user_has_group_request.id_sender')
                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'groups.created_on', 'user_has_group_request.id as requestid')
                ->where('id_receiver', '=', Auth::id())
                ->where('user_has_group_request.id', '=', $id)
                ->where('status', '=', 'pending')
                ->get();

        }  else if ($type == "group-accepted") {

            $req = DB::table('user_has_group_request')
                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
                ->join('users', 'users.id', '=', 'user_has_group_request.id_receiver')
                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'groups.created_on', 'user_has_group_request.id as requestid')
                ->where('id_sender', '=', Auth::id())
                ->where('status', '=', 'accepted')
                ->get();

        } else if ($type == "group-rejected") {

            $req = DB::table('user_has_group_request')
                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
                ->join('users', 'users.id', '=', 'user_has_group_request.id_receiver')
                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'groups.created_on', 'user_has_group_request.id as requestid')
                ->where('id_sender', '=', Auth::id())
                ->where('user_has_group_request.id', '=', $id)
                ->where('status', '=', 'rejected')
                ->get();

        } else if ($type == "meeting-request") {

            $req = DB::table('meetings AS m')
                ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
                ->join('users AS u', 'u.id', '=', 'm.host')
                ->where('m.id', '=', $id)
                ->where('um.userid', '=', Auth::id())
                ->where('m.host', '!=', Auth::id())
                ->where('m.status', '=', 'pending')->get();

        } else if ($type == "meeting-deny") {

            $req = DB::table('meetings AS m')
                ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
                ->join('users AS u', 'u.id', '=', 'm.host')
                ->where('m.id', '=', $id)
                ->where('um.userid', '=', Auth::id())
                ->where('m.host', '!=', Auth::id())
                ->where('m.status', '=', 'rejected')->get();

        } else if ($type == "meeting-accept") {

            $req = DB::table('meetings AS m')
                ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
                ->join('users AS u', 'u.id', '=', 'm.host')
                ->where('m.id', '=', $id)
                ->where('um.userid', '=', Auth::id())
                ->where('m.host', '!=', Auth::id())
                ->where('m.status', '=', 'accepted')->get();

        }

        if (count($req) > 0) {
            $req = $req[0];
        }
        return view('notification.view', compact('req', 'type'));

        // get details for that type
    }
}
