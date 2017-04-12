<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
use DataTimeZone;

class HomeController extends Controller
{
    public $timeTableStart = '08:00';
    public $timeTableEnd = '18:00';
    public $tableHeight = 550;
    public $timeTableWidth = 167;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meetings = DB::table('user_has_meeting AS u1')
            ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
                ->join('users', 'u2.userid', '=', 'users.id')
            ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
            ->where('meetings.status', '=', 'accepted')
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
            ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'groups.created_on', 'user_has_group_request.id as requestid')
            ->where('id_sender', '=', Auth::id())
            ->where('status', '=', 'accepted')
            ->get();


        $groupRequestRejected = DB::table('user_has_group_request')
            ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
            ->join('users', 'users.id', '=', 'user_has_group_request.id_receiver')
            ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'groups.created_on', 'user_has_group_request.id as requestid')
            ->where('id_sender', '=', Auth::id())
            ->where('status', '=', 'rejected')
            ->get();

//
        $groupRequestPending = DB::table('user_has_group_request')
            ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
            ->join('users', 'users.id', '=', 'user_has_group_request.id_sender')
            ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'groups.created_on', 'user_has_group_request.id as requestid')
            ->where('id_receiver', '=', Auth::id())
            ->where('status', '=', 'pending')
            ->get();

        $groups = DB::table('groups')
            ->join('user_has_group', 'user_has_group.id_group', '=', 'groups.id')
            ->join('users as u2', 'u2.id', '=', 'groups.id_creator')
            ->where('user_has_group.id_user', '=', Auth::id())
            ->select('groups.name as groupname', 'u2.name as creator', 'groups.created_on')
            ->orderby('groups.created_on', 'DESC')
            ->get();
        $active = 'dashboard';

        $allCourses[] = array();
        foreach ($courses as $course) {

            $app = app();
            $courseData = $app->make('stdClass');
            $courseData->name = $course->name;
            $courseData->timing = $course->timing;
            $courseData->section = $course->section;
            $courseData->height = $this->timeToMins($course->timing);
            $courseData->width = $this->timeTableWidth;
            $courseData->days = explode(',', $course->days);
            $courseData->max = $this->tableHeight;
            $courseData->min = 0;

            $allCourses[] = $courseData;
        }


        return view('home', compact('allCourses', 'courses', 'meetings', 'requests', 'user', 'groupRequestPending', 'groupRequestRejected', 'groupRequestAccepted', 'groups', 'active'));
    }

    public function seeNotifications(){
        $Notifications = DB::table('user_notifications')
            ->where('userlist', 'LIKE', '%,'.Auth::id().',%')->where('seen', '=', 'no')->get();
        if(count($Notifications) > 0){
            DB::table('user_notifications')
                ->where('userlist', 'LIKE', '%,'.Auth::id().',%')
                ->update(['seen' => 'yes']);
            return;
        } else{
            return;
        }
    }

    public  function Timetable(){
        $allCourses[] = array();
        $courses = DB::table('user_has_course')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('user_has_course.userid', '=', Auth::id())->get();
        $app = app();

        $meetings = $this->getUserMeetings(Auth::id());

        foreach ($courses as $course) {


            $courseData = $app->make('stdClass');
            $courseData->type = 'course';
            $courseData->name = $course->name;
            $courseData->timing = $course->timing;
            $courseData->section = $course->section;
            $courseData->height = $this->timeToMins($course->timing);
            $courseData->width = $this->timeTableWidth;
            $courseData->days = explode(',', $course->days);
            $courseData->max = $this->tableHeight;
            $courseData->min = 0;
            $courseData->startingHeight = $this->startingHeight($course->timing);
            $courseData->color = "";
            $allCourses[] = $courseData;
        }

        foreach ($meetings as $meeting) {

            if ($meeting->status == "accepted") {
                $meetingData = $app->make('stdClass');
                $meetingData->type = 'meeting';
                $meetingData->meetingid = 'meeting_'.$meeting->meetingid;
                $meetingData->with = $meeting->host;
                $meetingData->name = $meeting->date;
                $meetingData->timing = $meeting->time;
                $meetingData->section = "";
                $meetingData->height = $this->timeToMins($meeting->time);
                $meetingData->width = $this->timeTableWidth;
                $meetingData->days = array($meeting->day);
                $meetingData->max = $this->tableHeight;
                $meetingData->min = 0;
                $meetingData->color = "#3EA608";
                $meetingData->startingHeight = $this->startingHeight($meeting->time);

                $allCourses[] = $meetingData;
            }

        }

        $active = 'timetable';
        return view('timetable', compact('allCourses', 'active'));
    }

    public function timeToMins($time){
        $startTime = strtotime(explode('-', $time)[0]);
        $endTime = strtotime(explode('-', $time)[1]);
        $minutes = abs($endTime - $startTime) / 60;

        $totalMinutes = abs(strtotime($this->timeTableEnd) - strtotime($this->timeTableStart)) / 60;

        return $minutes/$totalMinutes * $this->tableHeight;
    }

    public function startingHeight($time){
        $startTime = strtotime($this->timeTableStart);
        $endTime = strtotime(explode('-', $time)[0]);
        if ($endTime == $startTime) {
            return 0;
        }
        $minutes = abs($endTime - $startTime) / 60;

        $totalHeight = ($this->tableHeight/(abs(strtotime($this->timeTableEnd) - strtotime($this->timeTableStart)) / 60)) * $minutes;

        return $totalHeight;
    }

    /**
     * http://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
     *
     * X time ago
     *
     * @param $datetime
     * @param bool $full
     * @return string
     */
    public function timeElapsed($datetime, $full = false) {
        $tz = new DateTimeZone('Asia/Karachi');
        $now = new DateTime('now', $tz);
        $ago = new DateTime($datetime, $tz);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }


}
