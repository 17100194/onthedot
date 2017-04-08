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
    public $tableHeight = 550;
    public $timeTableWidth = 155;

    public function index()
    {
        $meetings = DB::table('user_has_meeting AS u1')
            ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
            ->join('users', 'u2.userid', '=', 'users.id')
            ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
            ->where('u1.userid', '=', Auth::id())
            ->where('u2.userid', '!=', Auth::id())
            ->get();
        $active = 'view-meeting';
        return view('meetings.scheduled', compact('meetings', 'active'));
    }

    public function requested()
    {
        $meetings = DB::table('user_has_meeting AS u1')
            ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
            ->join('users', 'u2.userid', '=', 'users.id')
            ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
            ->where('meetings.status', '=', 'pending')
            ->where('meetings.host', '=', Auth::id())
            ->where('u1.userid', '=', Auth::id())
            ->where('u2.userid', '!=', Auth::id())->get();
        $active = 'requested';
        return view('meetings.requested', compact('meetings', 'active'));
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
        $query = $request->input('q');
        $allCourses[] = array();
        $active = 'meeting';
        $users = [];
        $groups = [];
        $usercourses = [];
        $meetingList = [];

        if (!$query) {
            $active = 'meeting';
            return view('meetings.search', compact('allCourses', 'users', 'usercourses', 'query', 'groups', 'active'));
        }
        $groups = DB::table('groups')->where('name', 'LIKE', '%' . $query . '%')->paginate(10);
        foreach ($groups as $group){
            $groupInfo = $this->getGroupById($group->id);
            $group->creator_name = $this->getUserById($group->id_creator);
            $group->members = $groupInfo->members;
            $group->userlist = $groupInfo->userlist;
        }


        $users = DB::table('users')->where('type', '=', 'student')->where(function ($q) use ($query){
            $q->where('name', 'LIKE', '%' . $query . '%')->orwhere('campusid', 'LIKE', '%' . $query . '%');
        })->paginate(10);

        $loggedInCoursesQ = DB::table('users')
            ->join('user_has_course', 'users.id', '=', 'user_has_course.userid')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('users.id', '=', Auth::id())
            ->select('users.name as userName', 'courses.name as courseName', 'courses.timing', 'courses.days', 'courses.section', 'users.campusid', 'users.id as userID')
            ->orderby('courses.timing', 'DESC')
            ->get();


//        $loggedInMeetings = array();
        //logged in data
        $loggedInCourses = array();
        $loggedInMeetingsQ = $this->getUserMeetings(Auth::id())->all();

        foreach($loggedInCoursesQ as $course) {
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
            $courseData->startingHeight = $this->startingHeight($course->timing);
            $courseData->color = "#2ca02c";
            $courseData->loggedIn = true;

            $loggedInCourses[] = $courseData;
        }

        if (count($loggedInMeetingsQ) > 0) {
            foreach($loggedInMeetingsQ as $meeting) {
                if ($meeting->status == "accepted") {
                    $app = app();
                    $meetingData = $app->make('stdClass');
                    $meetingData->name = $meeting->date;
                    $meetingData->timing = $meeting->time;
                    $meetingData->section = "";
                    $meetingData->height = $this->timeToMins($meeting->time);
                    $meetingData->width = $this->timeTableWidth;
                    $meetingData->days = array($meeting->day);
                    $meetingData->max = $this->tableHeight;
                    $meetingData->min = 0;
                    $meetingData->startingHeight = $this->startingHeight($meeting->time);
                    $meetingData->color = "#2ca02c";
                    $meetingData->loggedIn = true;

                    $meetingList[] = $meetingData;
                }
            }
        }
        else {
            $meetingList = [];
        }

        // loggedin users
        // foreach groups
        //      foreach members as member
        //      add logged in user at end

        $groupMap = array();

        if (count($groups) > 0) {
            foreach ($groups as $group) {
                $aggregate = array();
                foreach($group->members as $user) {
                    $userCourses = DB::table('users')
                        ->join('user_has_course', 'users.id', '=', 'user_has_course.userid')
                        ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
                        ->where('users.id', '=', $user)
                        ->where('users.id', '!=', Auth::id())
                        ->select('users.name as userName', 'courses.name as courseName', 'courses.timing', 'courses.days', 'courses.section', 'users.campusid', 'users.id as userID')
                        ->orderby('courses.timing', 'DESC')
                        ->get();
                    if (Auth::id() != $user) {
                        $userMeetings = $this->getUserMeetings($user)->all();

                        $meetingArr = array();

                        foreach($userMeetings as $meeting) {
                            if ($meeting->status == "accepted") {
                                $app = app();
                                $meetingData = $app->make('stdClass');
                                $meetingData->name = $meeting->date;
                                $meetingData->timing = $meeting->time;
                                $meetingData->section = "";
                                $meetingData->height = $this->timeToMins($meeting->time);
                                $meetingData->width = $this->timeTableWidth;
                                $meetingData->days = array($meeting->day);
                                $meetingData->max = $this->tableHeight;
                                $meetingData->min = 0;
                                $meetingData->startingHeight = $this->startingHeight($meeting->time);
                                $meetingData->color = "#2ca02c";
                                $meetingData->loggedIn = true;

                                $meetingArr[] = $meetingData;
                            }
                        }
                    } else {
                        $meetingArr = array();
                    }

                    $userData = array();

                    foreach($userCourses as $course) {
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
                        $courseData->startingHeight = $this->startingHeight($course->timing);
                        $courseData->color = "##3c948b";
                        $courseData->loggedIn = false;

                        $userData[] = $courseData;
                    }
                    $transition = array_merge($meetingArr, $userData);
                    $aggregate = array_merge($aggregate, $transition);
                }
                $temp = array_merge($meetingList, $loggedInCourses);
                $groupMap[$group->id] = array_merge($aggregate, $temp);
            }
        } else {
            $meetingList = array();
        }

        $hashMap = array();
        foreach ($users as $user) {
            $userCourses = DB::table('users')
                ->join('user_has_course', 'users.id', '=', 'user_has_course.userid')
                ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
                ->where('users.id', '=', $user->id)
                ->where('users.id', '!=', Auth::id())
                ->select('users.name as userName', 'courses.name as courseName', 'courses.timing', 'courses.days', 'courses.section', 'users.campusid', 'users.id as userID')
                ->orderby('courses.timing', 'DESC')
                ->get();

            $userData = array();

            foreach($userCourses as $course) {
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
                $courseData->startingHeight = $this->startingHeight($course->timing);
                $courseData->color = "##3c948b";
                $courseData->loggedIn = false;

                $userData[] = $courseData;
            }
            $array1 = array_merge($loggedInCourses, $userData);

            $hashMap[$user->id] = array_merge($array1, $meetingList);

        }

        return view('meetings.search', compact('allCourses', 'users', 'hashMap', 'query', 'groups', 'active', 'groupMap'));
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

    public function singleConflict($userid, $time, $day, $date){

        $user1Courses = $this->getUserCourses($userid);

        $user1Meetings = $this->getUserMeetings($userid);


        $ret = true;
        foreach ($user1Courses as $course) {
            $courseDays =  explode(',', $course->days);
            foreach ($courseDays as $thisDay) {
                if ($thisDay == $day) {
                    // this course is on the day of the meeting
                    $startTime = explode('-', $course->timing)[0];
                    $endTime = explode('-', $course->timing)[1];

                    $startTimeMeeting = explode('-', $time)[0];
                    $endTimeMeeting = explode('-', $time)[1];
                    if((strtotime($startTimeMeeting) >= strtotime($startTime) && strtotime($startTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($endTimeMeeting) >= strtotime($startTime) && strtotime($endTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($startTimeMeeting) <= strtotime($startTime) && strtotime($endTimeMeeting) >= strtotime($endTime))){
                        $ret = false;
                        return false;
                    } else {
                        $ret = true;
                    }
                }
            }
        }

        // check for meetings
        foreach ($user1Meetings as $meeting) {
            if ($meeting->status == "accepted") {
                $meetingDay =  $meeting->day;
                if ($meetingDay == $day) {
                    // this meeting is on the day of the meeting
                    $startTime = explode('-', $meeting->time)[0];
                    $endTime = explode('-', $meeting->time)[1];

                    $startTimeMeeting = explode('-', $time)[0];
                    $endTimeMeeting = explode('-', $time)[1];
                    if((strtotime($startTimeMeeting) >= strtotime($startTime) && strtotime($startTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($endTimeMeeting) >= strtotime($startTime) && strtotime($endTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($startTimeMeeting) <= strtotime($startTime) && strtotime($endTimeMeeting) >= strtotime($endTime))){
                        $ret = false;
                        return false;
                    } else {
                        $ret = true;
                    }
                }
            }

        }

        return $ret;
    }

    public function checkConflict($hostid, $userid, $time, $day, $date){
        // get day
        // get courses of both users
        // check courses for that day for both users
        $user1Courses = $this->getUserCourses($userid);
        $user2Courses = $this->getUserCourses($hostid);

        $user1Meetings = $this->getUserMeetings($userid);
        $user2Meetings = $this->getUserMeetings($hostid);


        $ret = true;
        foreach ($user1Courses as $course) {
            $courseDays =  explode(',', $course->days);
            foreach ($courseDays as $thisDay) {
                if ($thisDay == $day) {
                    // this course is on the day of the meeting
                    $startTime = explode('-', $course->timing)[0];
                    $endTime = explode('-', $course->timing)[1];

                    $startTimeMeeting = explode('-', $time)[0];
                    $endTimeMeeting = explode('-', $time)[1];
                    if((strtotime($startTimeMeeting) >= strtotime($startTime) && strtotime($startTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($endTimeMeeting) >= strtotime($startTime) && strtotime($endTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($startTimeMeeting) <= strtotime($startTime) && strtotime($endTimeMeeting) >= strtotime($endTime))){
                        $ret = false;
                        return false;
                    } else {
                        $ret = true;
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
                    if ((strtotime($startTimeMeeting) >= strtotime($startTime) && strtotime($startTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($endTimeMeeting) >= strtotime($startTime) && strtotime($endTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($startTimeMeeting) <= strtotime($startTime) && strtotime($endTimeMeeting) >= strtotime($endTime))) {
                        $ret = false;
                        return false;
                    } else {
                        $ret = true;
                    }
                }
            }
        }

        // check for meetings
        foreach ($user1Meetings as $meeting) {
            if ($meeting->status == "accepted") {
                $meetingDay =  $meeting->day;
                if ($meetingDay == $day) {
                    // this meeting is on the day of the meeting
                    $startTime = explode('-', $meeting->time)[0];
                    $endTime = explode('-', $meeting->time)[1];

                    $startTimeMeeting = explode('-', $time)[0];
                    $endTimeMeeting = explode('-', $time)[1];
                    if((strtotime($startTimeMeeting) >= strtotime($startTime) && strtotime($startTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($endTimeMeeting) >= strtotime($startTime) && strtotime($endTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($startTimeMeeting) <= strtotime($startTime) && strtotime($endTimeMeeting) >= strtotime($endTime))){
                        $ret = false;
                        return false;
                    } else {
                        $ret = true;
                    }
                }
            }

        }

        foreach ($user2Meetings as $meeting) {
            if ($meeting->status == "accepted") {
                $meetingDay =  $meeting->day;
                if ($meetingDay == $day) {
                    // this meeting is on the day of the meeting
                    $startTime = explode('-', $meeting->time)[0];
                    $endTime = explode('-', $meeting->time)[1];

                    $startTimeMeeting = explode('-', $time)[0];
                    $endTimeMeeting = explode('-', $time)[1];
                    if((strtotime($startTimeMeeting) >= strtotime($startTime) && strtotime($startTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($endTimeMeeting) >= strtotime($startTime) && strtotime($endTimeMeeting) <= strtotime($endTime)) ||
                        (strtotime($startTimeMeeting) <= strtotime($startTime) && strtotime($endTimeMeeting) >= strtotime($endTime))){
                        $ret = false;
                        return false;
                    } else {
                        $ret = true;
                    }
                }
            }

        }


        return $ret;
    }

    public function schedule(Request $request)
    {
        $time = $request->Time;
        $day = $request->Day;
        $date = $request->Date;
        $userid = $request->User;
        $time = str_replace(" ","",$time);
        if($this->checkConflict(Auth::id(), $userid, $time, $day, $date) == false) {
            return 'error';
        }

        $insert = DB::table('meetings')->insertGetId(['time'=>strval($time), 'day'=>strval($day), 'date'=>strval($date), 'host'=>strval(Auth::id()), 'status' => 'pending']);
        DB::table('user_has_meeting')->insert(array('userid'=>Auth::id(), 'meetingid'=>$insert));
        DB::table('user_has_meeting')->insert(array('userid'=>$userid, 'meetingid'=>$insert));

        // attach notification

        $notificationList = ','.$userid.',';
        $loggedIn = $this->getUserById(Auth::id());
        $html = '<strong>'.$loggedIn->name . ' ('.$loggedIn->campusid.')</strong> has requested to meet you at '.strval($time) .' - ' . strval($date);
        DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'meeting', 'userlist' => $notificationList));


        return 'success';
    }

    public function scheduleGroupMeeting(Request $request){
        $time = $request->Time;
        $day = $request->Day;
        $date = $request->Date;
        $groupid = $request->Group;
        $time = str_replace(" ","",$time);
        $group = $this->getGroupById($groupid);

        $first = true;
        foreach ($group->members as $user) {
            if($this->singleConflict($user, $time, $day, $date) == false) {
                return 'error';
            }
            if ($first) {
                $insert = DB::table('meetings')->insertGetId(['time'=>strval($time), 'day'=>strval($day), 'date'=>strval($date), 'host'=>strval(Auth::id()), 'status' => 'pending']);
                $first = false;
            }
            DB::table('user_has_meeting')->insert(array('userid'=>$user, 'meetingid'=>$insert));

        }

        return "success";
    }

    public function accept(Request $request) {
        $meetingid = $request->meetingid;
        DB::table('meetings')->where('id', '=', $meetingid)
            ->update(['status' => 'accepted']);

        $meeting = $this->getMeetingById($meetingid);

        $notificationList = ','.$meeting->host.',';
        $loggedIn = $this->getUserById(Auth::id());
        $html = '<strong>'.$loggedIn->name . ' ('.$loggedIn->campusid.')</strong> has accepted your request for meeting at '.$meeting->time .' - ' . $meeting->date;
        DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'meeting', 'userlist' => $notificationList));

    }

    public function reject(Request $request) {
        $meetingid = $request->meetingid;
        $message = $request->message;
        DB::table('meetings')->where('id', '=', $meetingid)
            ->update([
                'status' => 'rejected',
                'message' => $message
            ]);

        $meeting = $this->getMeetingById($meetingid);
        $loggedIn = $this->getUserById(Auth::id());
        $notificationList = ','.$meeting->host.',';
        $html = '<strong>'.$loggedIn->name . ' ('.$loggedIn->campusid.')</strong>'. ' has rejected your request to meet. Reason: <strong> '.$message .'</strong>';
        DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'meeting', 'userlist' => $notificationList));

    }
}
