<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class MeetingsController extends Controller
{
    public $timeTableStart = '08:00';
    public $timeTableEnd = '18:00';
    public $tableHeight = 629;
    public $timeTableWidth = 155;

    public function index()
    {
        $meetings = DB::table('user_has_meeting AS u1')
            ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
            ->join('users', 'u2.userid', '=', 'users.id')
            ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
            ->where('u1.userid', '=', Auth::id())
            ->where('u2.userid', '!=', Auth::id())->get();
        $active = 'view-meeting';
        return view('meetings.scheduled', compact('meetings', 'active'));
    }

    public function cancelMeeting(Request $request) {
        $meetingid = $request->meetingid;
        DB::table('user_has_meeting')->where('userid', '=', Auth::id())->where('meetingid', '=', intval($meetingid))->delete();
        // get others notification that you left meeting
        $userlist = DB::table('user_has_meeting')->where('meetingid', '=', intval($meetingid))->get();

        $meeting = $this->getMeetingById($meetingid);

        $users = array();
        foreach ($userlist as $user) {
            $users[] = $user->userid;
        }

        $notificationList = implode(',', $users);
        $notificationList = ','.$notificationList.',';
        $loggedIn = $this->getUserById(Auth::id());
        $txt = '<strong>'.$loggedIn->name.' (' . $loggedIn->campusid . ')</strong> will not be attending the meeting hosted on <strong>'.$meeting->time .' - ' . $meeting->date .'</strong> .';
        DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'meeting', 'userlist' => $notificationList));
        if(count($userlist) < 2) {
            DB::table('meetings')->where('id', '=', intval($meetingid))->delete();
        }
        session(['message' => 'Meeting Cancelled Successfully']);
        return 'success';

    }

    public function requests(){
        $requests = DB::table('meetings AS m')
            ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
            ->join('users AS u', 'u.id', '=', 'm.host')
            ->where('um.userid', '=', Auth::id())
            ->where('m.host', '!=', Auth::id())
            ->where('m.status', '=', 'pending')->get();
        $active = 'requests';
        return view('meetings.requests', compact('requests', 'active'));
    }

    public function q(Request $request)
    {
        $query = $request->input('search');
        $groups = DB::table('groups')->where('name', 'LIKE', '%' . $query . '%')->paginate(10);
        foreach ($groups as $group){
            $group->creator_name = $this->getUserById($group->id_creator);
        }
        $users = DB::table('users')->where('name', 'LIKE', '%' . $query . '%')->orwhere('campusid', 'LIKE', '%' . $query . '%')->paginate(10);
        $usercourses = DB::table('users')
            ->join('user_has_course', 'users.id', '=', 'user_has_course.userid')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('users.name', 'LIKE', '%' . $query . '%')
            ->orwhere('users.campusid', 'LIKE', '%' . $query . '%')
            ->select('users.name as userName', 'courses.name as courseName', 'courses.timing', 'courses.days', 'courses.section', 'users.campusid', 'users.id as userID')
            ->orderby('courses.timing', 'DESC')
            ->get();
        $allCourses[] = array();
        foreach ($usercourses as $course) {

            $app = app();
            $courseData = $app->make('stdClass');
            $courseData->name = $course->courseName;
            $courseData->timing = $course->timing;
            $courseData->section = $course->section;
            $courseData->height = $this->timeToMins($course->timing);
            $courseData->width = $this->timeTableWidth;
            $courseData->days = explode(',', $course->days);
            $courseData->max = $this->tableHeight;
            $courseData->min = 0;
            $courseData->userid = $course->userID;
            $courseData->startingHeight = $this->startingHeight($course->timing);

            $allCourses[] = $courseData;
        }
        $active = 'meeting';
        return view('meetings.search', compact('allCourses', 'users', 'usercourses', 'query', 'groups', 'active'));
    }

    public function timeToMins($time) {
        $startTime = strtotime(explode('-', $time)[0]);
        $endTime = strtotime(explode('-', $time)[1]);
        $minutes = abs($endTime - $startTime) / 60;

        $totalMinutes = abs(strtotime($this->timeTableEnd) - strtotime($this->timeTableStart)) / 60;

        return $minutes/$totalMinutes * $this->tableHeight;
    }

    public function startingHeight($time) {
        $startTime = strtotime($this->timeTableStart);
        $endTime = strtotime(explode('-', $time)[0]);
        if ($endTime == $startTime) {
            return 0;
        }
        $minutes = abs($endTime - $startTime) / 60;

        $totalHeight = ($this->tableHeight/(abs(strtotime($this->timeTableEnd) - strtotime($this->timeTableStart)) / 60)) * $minutes;

        return $totalHeight;
    }

    public function checkConflict($hostid, $userid, $time, $day, $date){
        // get day
        // get courses of both users
        // check courses for that day for both users
        $user1Courses = DB::table('user_has_course')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('user_has_course.userid', '=', $userid)->get();

        $user2Courses = DB::table('user_has_course')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('user_has_course.userid', '=', $hostid)->get();

        foreach ($user1Courses as $course) {
            $courseDays =  explode(',', $course->days);
            foreach ($courseDays as $thisDay) {
                if ($thisDay == $day) {
                    // this course is on the day of the meeting
                    $startTime = explode('-', $course->timing)[0];
                    $endTime = explode('-', $course->timing)[1];

                    $startTimeMeeting = explode('-', $time)[0];
                    $endTimeMeeting = explode('-', $time)[1];
                    if((strtotime($startTimeMeeting) > strtotime($startTime) && strtotime($startTimeMeeting) < strtotime($endTime)) ||
                        (strtotime($endTimeMeeting) > strtotime($startTime) && strtotime($endTimeMeeting) < strtotime($endTime)) ||
                        (strtotime($startTimeMeeting) < strtotime($startTime) && strtotime($endTimeMeeting) > strtotime($startTime))){
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        }

        foreach ($user2Courses as $course) {
            $courseDays =  explode(',', $course->days);
            foreach ($courseDays as $thisDay) {
                if ($thisDay == $day) {
                    // this course is on the day of the meeting
                    $startTime = explode('-', $course->timing)[0];
                    $endTime = explode('-', $course->timing)[1];

                    $startTimeMeeting = explode('-', $time)[0];
                    $endTimeMeeting = explode('-', $time)[1];
                    if((strtotime($startTimeMeeting) > strtotime($startTime) && strtotime($startTimeMeeting) < strtotime($endTime)) ||
                        (strtotime($endTimeMeeting) > strtotime($startTime) && strtotime($endTimeMeeting) < strtotime($endTime)) ||
                        (strtotime($startTimeMeeting) < strtotime($startTime) && strtotime($endTimeMeeting) > strtotime($startTime))){
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        }
    }

    public function schedule(Request $request)
    {
        $time = $request->Time;
        $day = $request->Day;
        $date = $request->Date;
        $userid = $request->User;
        $time = str_replace(" ","",$time);
        if($this->checkConflict(Auth::id(), $userid, $time, $day, $date) == false){
            return 'error';
        }
        $insert = DB::table('meetings')->insertGetId(['time'=>strval($time), 'day'=>strval($day), 'date'=>strval($date), 'host'=>strval(Auth::id()), 'status' => 'pending']);
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
