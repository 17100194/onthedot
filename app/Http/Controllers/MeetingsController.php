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
            ->get();

        return view('meetings.search', compact('users', 'usercourses', 'query'));
    }

    public function schedule(Request $request)
    {
        $time = $request->Time;
        $day = $request->Day;
        $date = $request->Date;
        $userid = $request->User;
        $insert = DB::table('meetings')->insertGetId(array('time'=>$time, 'day'=>$day, 'date'=>$date, 'host'=>Auth::id()));
        DB::table('user_has_meeting')->insert(array('userid'=>Auth::id(), 'meetingid'=>$insert));
        DB::table('user_has_meeting')->insert(array('userid'=>$userid, 'meetingid'=>$insert));
        return 'success';
    }
}
