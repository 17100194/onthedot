<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class MeetingsController extends Controller
{
    public function index()
    {
        $meetings = DB::table('user_has_meeting AS u1')
            ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
            ->join('users', 'u2.userid', '=', 'users.id')
            ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
            ->where('u1.userid', '=', Auth::id())
            ->where('u2.userid', '!=', Auth::id())->get();
        return view('meetings.scheduled', compact('meetings'));
    }

    public function requests(){
        $requests = DB::table('meetings AS m')
            ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
            ->join('users AS u', 'u.id', '=', 'm.host')
            ->where('um.userid', '=', Auth::id())
            ->where('m.host', '!=', Auth::id())
            ->where('m.status', '=', 'pending')->get();
        return view('meetings.requests', compact('requests'));
    }

    public function q(Request $request)
    {
        $query = $request->input('search');
        $users = DB::table('users')->where('name', 'LIKE', '%' . $query . '%')->orwhere('campusid', 'LIKE', '%' . $query . '%')->paginate(10);
        $usercourses = DB::table('users')
            ->join('user_has_course', 'users.id', '=', 'user_has_course.userid')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('users.name', 'LIKE', '%' . $query . '%')
            ->orwhere('users.campusid', 'LIKE', '%' . $query . '%')
            ->select('users.name as userName', 'courses.name as courseName', 'courses.timing', 'courses.days', 'courses.section', 'users.campusid', 'users.id as userID')
            ->orderby('courses.timing', 'DESC')
            ->get();

        return view('meetings.search', compact('users', 'usercourses', 'query'));
    }

    public function schedule(Request $request)
    {
        $time = $request->Time;
        $day = $request->Day;
        $date = $request->Date;
        $userid = $request->User;
        $insert = DB::table('meetings')->insertGetId(array('time'=>strval($time), 'day'=>strval($day), 'date'=>strval($date), 'host'=>Auth::id(), 'status' => 'pending'));
        DB::table('user_has_meeting')->insert(array('userid'=>Auth::id(), 'meetingid'=>$insert));
        DB::table('user_has_meeting')->insert(array('userid'=>$userid, 'meetingid'=>$insert));
        return 'success';
    }

    public function accept(Request $request) {
        $meetingid = $request->meetingid;
        DB::table('meetings')->where('id', '=', $meetingid)
            ->update(['status' => 'accepted']);
    }

    public function reject(Request $request) {
        $meetingid = $request->meetingid;
        $message = $request->message;
        DB::table('meetings')->where('id', '=', $meetingid)
            ->update([
                'status' => 'rejected',
                'message' => $message
            ]);
    }
}
