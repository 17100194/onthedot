<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
use DataTimeZone;
use Validator;
use Hash;

class HomeController extends Controller
{
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
            ->where('u2.userid', '!=', Auth::id())
            ->orderBy('date','desc')
            ->limit(6)->get();

        $requests = DB::table('meetings AS m')
            ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
            ->join('users AS u', 'u.id', '=', 'm.host')
            ->where('um.userid', '=', Auth::id())
            ->where('m.host', '!=', Auth::id())
            ->where('m.status', '=', 'pending')->get();

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

        return view('home',compact('meetings','courses', 'groups', 'active'));
    }

    public function showTimetable(Request $request){
        $monday = $request->monday;
        $tuesday = $request->tuesday;
        $wednesday = $request->wednesday;
        $thursday = $request->thursday;
        $friday = $request->friday;
        if ($monday == null){
            $monday = date('Y-m-d',strtotime('monday',strtotime('previous saturday')));
        } else{
            if ($request->button == 'next'){
                $monday = date('Y-m-d', strtotime($monday. ' + 7 days'));
            } else{
                $monday = date('Y-m-d', strtotime($monday. ' - 7 days'));
            }
        }
        if ($tuesday == null){
            $tuesday = date('Y-m-d',strtotime('tuesday',strtotime('previous saturday')));
        } else {
            if ($request->button == 'next'){
                $tuesday = date('Y-m-d', strtotime($tuesday. ' + 7 days'));
            } else{
                $tuesday = date('Y-m-d', strtotime($tuesday. ' - 7 days'));
            }
        }
        if ($wednesday == null){
            $wednesday = date('Y-m-d',strtotime('wednesday',strtotime('previous saturday')));
        } else {
            if ($request->button == 'next'){
                $wednesday = date('Y-m-d', strtotime($wednesday. ' + 7 days'));
            } else{
                $wednesday = date('Y-m-d', strtotime($wednesday. ' - 7 days'));
            }
        }
        if ($thursday == null){
            $thursday = date('Y-m-d',strtotime('thursday',strtotime('previous saturday')));
        } else {
            if ($request->button == 'next'){
                $thursday = date('Y-m-d', strtotime($thursday. ' + 7 days'));
            } else{
                $thursday = date('Y-m-d', strtotime($thursday. ' - 7 days'));
            }
        }
        if ($friday == null){
            $friday = date('Y-m-d',strtotime('friday',strtotime('previous saturday')));
        } else {
            if ($request->button == 'next'){
                $friday = date('Y-m-d', strtotime($friday. ' + 7 days'));
            } else{
                $friday = date('Y-m-d', strtotime($friday. ' - 7 days'));
            }
        }
        $allCourses[] = array();
        $courses = DB::table('user_has_course')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('user_has_course.userid', '=', Auth::id())->get();
        $app = app();

        $meetings = $this->getUserMeetings(Auth::id());
        $meetingsObject = $this->getUserMeetingsObject(Auth::id());

        foreach ($courses as $course) {
            $instructor = $this->getUserById($course->instructorid);
            $courseData = $app->make('stdClass');
            $courseData->type = 'course';
            $time = date("g:i a", strtotime(explode('-',$course->timing)[0])).'-'.date("g:i a", strtotime(explode('-',$course->timing)[1]));
            $courseData->content = "<div class='text-center'><h3 class='text-shadow-dark'>$course->name ($course->coursecode)</h3><div class='separator'><span>Course Details</span></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Instructor</label> $instructor->name</h3></div></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Section</label> $course->section</h3></div></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Timing</label> $time</h3></div></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Days</label> $course->days</h3></div></div></div>";
            $courseData->meetingid = "";
            $courseData->code = $course->coursecode;
            $courseData->with = "";
            $courseData->name = $course->name;
            $courseData->timing = $course->timing;
            $courseData->section = $course->section;
            $courseData->days = explode(',', $course->days);
            $allCourses[] = $courseData;
        }

        foreach ($meetings as $meeting) {
            $with = array();
            foreach($meetingsObject[$meeting->meetingid]->members as $member) {
                if ($member->userid != Auth::id()){
                    $with[]= $this->getUserById($member->userid)->name;
                }
            }
            $with = implode(', ', $with);
            $date = str_replace("/", "-", $meeting->date);
            if ($meeting->status == "accepted" && $meeting->status_meeting == 'accepted' && ((date('Y-m-d',strtotime($date)) == $monday)||(date('Y-m-d',strtotime($date)) == $tuesday)||(date('Y-m-d',strtotime($date)) == $wednesday)||(date('Y-m-d',strtotime($date)) == $thursday)||(date('Y-m-d',strtotime($date)) == $friday))){
                $meetingData = $app->make('stdClass');
                $time = date("g:i a", strtotime(explode('-',$meeting->time)[0])).'-'.date("g:i a", strtotime(explode('-',$meeting->time)[1]));
                $meetingData->type = 'meeting';
                $start = strtotime(date('Y-m-d H:i',strtotime($date." ".explode('-',$meeting->time)[0])));
                $end = strtotime(date('Y-m-d H:i',strtotime($date." ".explode('-',$meeting->time)[1])));
                $now = strtotime('now');
                if ($now >= $start && $now <= $end) {
                    $meetingData->content = "<div class='text-center'><h3 class='text-shadow-dark'>Meeting Details</h3><div class='separator'><i class='fa fa-handshake-o'></i></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>With</label> $with</h3></div></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Time</label> $time</h3></div></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Date</label> $meeting->date</h3></div></div><div class='separator'><span>Meeting Status</span></div><h4 class='text-info'>In Progress</h4></div>";
                } elseif ($now < $start) {
                    $meetingData->content = "<div class='text-center'><h3 class='text-shadow-dark'>Meeting Details</h3><div class='separator'><i class='fa fa-handshake-o'></i></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>With</label> $with</h3></div></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Time</label> $time</h3></div></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Date</label> $meeting->date</h3></div></div><div class='separator'><span>Meeting Status</span></div><h4 class='text-info'>Not Started</h4></div>";
                } else {
                    $meetingData->content = "<div class='text-center'><h3 class='text-shadow-dark'>Meeting Details</h3><div class='separator'><i class='fa fa-handshake-o'></i></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>With</label> $with</h3></div></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Time</label> $time</h3></div></div><div class='row'><div class='col-xs-12'><h3><label class='label label-info'>Date</label> $meeting->date</h3></div></div><div class='separator'><span>Meeting Status</span></div><h4 class='text-info'>Finished</h4></div>";
                }
                $meetingData->meetingid = 'meeting_'.$meeting->meetingid;
                $meetingData->with = $with;
                $meetingData->name = $meeting->date;
                $meetingData->timing = $meeting->time;
                $meetingData->code = "";
                $meetingData->section = "";
                $meetingData->days = array($meeting->day);

                $allCourses[] = $meetingData;
            }
        }
        $tmp1 = array();
        foreach($allCourses as $key => $value) {
            if ($value != null){
                if(!array_key_exists($value->meetingid,$tmp1)) {
                    $tmp1[$value->meetingid] = $value;
                }
            }
        }
        $allCourses = array_values($tmp1);
        return view('timetable_result',['allCourses'=>$allCourses,'monday'=>$monday,'tuesday'=>$tuesday,'wednesday'=>$wednesday,'thursday'=>$thursday,'friday'=>$friday])->render();
    }

    public function Settings(){
        return view('account.settings');
    }

    public function updatePassword(Request $request){
        $user = Auth::user();
        $validation = Validator::make($request->all(), [
            'old_password' => 'required|hash:' . $user->password,
            'password' => 'required|min:6|different:old_password|confirmed'
        ]);
        if ($validation->fails()){
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['success' => '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-check-circle"></i> Password changed successfully!</div>']);
    }

    public  function Timetable(){
        $active = 'timetable';
        return view('timetable', compact('active'));
    }
}
