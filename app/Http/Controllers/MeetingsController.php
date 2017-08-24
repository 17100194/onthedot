<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MeetingsController extends Controller
{
    public function index()
    {
        $query = DB::table('user_has_meeting AS u1')
            ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
            ->join('users', 'u2.userid', '=', 'users.id')
            ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
            ->where('meetings.status', '=', 'accepted')
            ->where('u1.userid', '=', Auth::id())
            ->where('u2.userid', '!=', Auth::id())
            ->orderBy('meetings.created_on','desc')
            ->select('users.name as name','meetings.id as id','meetings.date as date')
            ->get()
            ->groupBy('id');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($query);
        $perPage = 10;
        $currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $meetings = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);
        $active = 'view-meeting';
        return view('meetings.scheduled', compact('meetings', 'active'));
    }

    public function meetingDetails(Request $request){
        $meeting = DB::table('meetings')
            ->join('user_has_meeting', 'user_has_meeting.meetingid', '=', 'meetings.id')
            ->join('users', 'users.id', '=', 'user_has_meeting.userid')
            ->where('users.id', '!=', Auth::id())
            ->where('meetings.id', '=', $request->meetingid)
            ->select('meetings.time as time','meetings.type as type', 'meetings.venue as venue', 'meetings.host as host', 'meetings.date as date', 'meetings.day as day','users.id as userid', 'users.name as name', 'meetings.id as id')->get()->groupBy('id')->first();
        if ($meeting->first()->type == 'group'){
            $users = array();
            foreach ($meeting as $value){
                $userstatus = DB::table('user_has_meeting')
                    ->join('users','users.id','=','user_has_meeting.userid')
                    ->where('user_has_meeting.userid','=',$value->userid)
                    ->where('user_has_meeting.meetingid','=',$request->meetingid)
                    ->first();
                $users[] = $userstatus;
            }
            $meeting->users = $users;
        }
        $date = str_replace("/", "-", $meeting->first()->date);
        $starttime = date('Y-m-d H:i',strtotime($date." ".explode('-',$meeting->first()->time)[0]));
        $endtime = strtotime(date('Y-m-d H:i',strtotime($date." ".explode('-',$meeting->first()->time)[1])));
        return view('meetings.meeting_details', ['meeting' => $meeting, 'starttime'=>$starttime,'endtime'=>$endtime])->render();
    }

    public function sentRequestDetails(Request $request){
        $meeting = DB::table('user_has_meeting AS u1')
            ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
            ->join('users', 'u2.userid', '=', 'users.id')
            ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
            ->where('meetings.status', '=', 'pending')
            ->where('meetings.host', '=', Auth::id())
            ->where('meetings.id','=',$request->meetingid)
            ->where('u1.userid', '=', Auth::id())
            ->where('u2.userid', '!=', Auth::id())
            ->select('meetings.time as time','meetings.type as type', 'meetings.host as host', 'meetings.date as date', 'meetings.day as day','users.id as userid', 'users.name as name', 'meetings.id as id')->get()->groupBy('id')->first();
        if ($meeting->first()->type == 'group'){
            $users = array();
            foreach ($meeting as $value){
                $userstatus = DB::table('user_has_meeting')
                    ->join('users','users.id','=','user_has_meeting.userid')
                    ->where('user_has_meeting.userid','=',$value->userid)
                    ->where('user_has_meeting.meetingid','=',$request->meetingid)
                    ->first();
                $users[] = $userstatus;
            }
            $meeting->users = $users;
        }
        return view('meetings.request_sent_details',['meeting'=>$meeting])->render();
    }

    public function requested()
    {
        $query = DB::table('user_has_meeting AS u1')
            ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
            ->join('users', 'u2.userid', '=', 'users.id')
            ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
            ->where('meetings.status', '=', 'pending')
            ->where('meetings.host', '=', Auth::id())
            ->where('u1.userid', '=', Auth::id())
            ->where('u2.userid', '!=', Auth::id())
            ->orderBy('meetings.created_on','desc')
            ->select('users.name as name','meetings.id as id','meetings.date as date')
            ->get()
            ->groupBy('id');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($query);
        $perPage = 10;
        $currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $meetings = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);
        $active = 'requested';
        return view('meetings.requested', compact('meetings', 'active'));
    }

    public function cancelMeeting(Request $request) {
        $meetingid = $request->meetingid;
        $meeting = $this->getMeetingById($meetingid);
        DB::table('user_has_meeting')->where('userid', '=', Auth::id())->where('meetingid', '=', intval($meetingid))->delete();
        $userlist = DB::table('user_has_meeting')->where('meetingid', '=', intval($meetingid))->get();
        if ($meeting->type == 'group' && $meeting->host == Auth::id()){
            DB::table('user_has_meeting')->where('meetingid', '=', intval($meetingid))->delete();
            DB::table('meetings')->where('id', '=', intval($meetingid))->delete();
            foreach ($userlist as $user) {
                $notificationList = ','.$user->userid.',';
                $loggedIn = $this->getUserById(Auth::id());
                $txt = '<span class="label label-info">'.$loggedIn->name.' (' . $loggedIn->campusid . ')</span> has cancelled the meeting hosted on <span class="label label-info">'.date('F d,Y',strtotime($meeting->date)).'</span> at <span class="label label-info">'.date('h:iA',strtotime(explode('-',$meeting->time)[0])).' - '.date('h:iA',strtotime(explode('-',$meeting->time)[1])).'</span>';
                DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'meeting', 'userlist' => $notificationList));
            }
            return;
        } else {
            foreach ($userlist as $user) {
                $notificationList = ','.$user->userid.',';
                $loggedIn = $this->getUserById(Auth::id());
                $txt = '<span class="label label-info">'.$loggedIn->name.' (' . $loggedIn->campusid . ')</span> will not be able to attend the meeting hosted on <span class="label label-info">'.date('F d,Y',strtotime($meeting->date)).'</span> at <span class="label label-info">'.date('h:iA',strtotime(explode('-',$meeting->time)[0])).' - '.date('h:iA',strtotime(explode('-',$meeting->time)[1])).'</span>';
                DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'meeting', 'userlist' => $notificationList));
            }
            if(count($userlist) < 2) {
                DB::table('meetings')->where('id', '=', intval($meetingid))->delete();
                DB::table('user_has_meeting')->where('meetingid','=',intval($meetingid))->delete();
            }
            $request->session()->flash('message', 'Meeting request cancelled successfully!');
            return;
        }
    }

    public function requestDetails(Request $request){
        $request = DB::table('meetings AS m')
            ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
            ->join('users AS u', 'u.id', '=', 'm.host')
            ->where('um.userid', '=', Auth::id())
            ->where('m.host', '!=', Auth::id())
            ->where('m.id', '=', $request->meetingid)->first();
        return view('meetings.request_details',['request'=>$request])->render();
    }

    public function getTimetable(Request $request){
        $id = $request->id;
        $type = $request->type;
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
        if ($type == 'student'){
            $courses = DB::table('user_has_course')
                ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
                ->where('user_has_course.userid', '=', Auth::id())
                ->orwhere('user_has_course.userid', '=', $id)
                ->get();
            $courses = $courses->toArray();
            $tmp1 = array();
            foreach($courses as $key => $value) {
                if(!array_key_exists($value->coursecode,$tmp1)) {
                    $tmp1[$value->coursecode] = $value;
                    $tmp1[$value->coursecode]->who = '';
                } else {
                    $tmp1[$value->coursecode]->who = 'common';
                }
            }
            $courses = array_values($tmp1);
            foreach ($courses as $course){
                $course->type = 'course';
                $course->days = explode(',',$course->days);
                if ($course->userid == Auth::id() && $course->who != 'common'){
                    $course->who = 'me';
                } else {
                    if ($course->who != 'common'){
                        $course->who = 'user';
                    }
                }
            }
            $meetings = DB::table('user_has_meeting')
                ->join('meetings', 'user_has_meeting.meetingid', '=', 'meetings.id')
                ->where('user_has_meeting.userid', '=', Auth::id())
                ->where('meetings.status','=','accepted')
                ->where('user_has_meeting.status_meeting','=','accepted')
                ->orwhere('user_has_meeting.userid', '=', $id)
                ->where('meetings.status','=','accepted')
                ->where('user_has_meeting.status_meeting','=','accepted')
                ->get();
            $meetings = $meetings->toArray();
            $tmp2 = array();
            foreach($meetings as $key => $value) {
                if(!array_key_exists($value->meetingid,$tmp2)) {
                    $tmp2[$value->meetingid] = $value;
                    $tmp2[$value->meetingid]->who = '';
                } else {
                    $tmp2[$value->meetingid]->who = 'common';
                }
            }
            $meetings = array_values($tmp2);
            foreach ($meetings as $meeting){
                $meeting->type = 'meeting';
                $meeting->days = array($meeting->day);
                if (($meeting->userid == Auth::id() || intval($meeting->host) == Auth::id()) && $meeting->who != 'common'){
                    $meeting->who = 'me';
                } else {
                    if ($meeting->who != 'common'){
                        $meeting->who = 'user';
                    }
                }
            }
            $allevents = array_merge($courses,$meetings);
            return view('meetings.student_timetable',['id'=>$id,'allevents'=>$allevents,'monday'=>$monday,'tuesday'=>$tuesday,'wednesday'=>$wednesday,'thursday'=>$thursday,'friday'=>$friday])->render();
        }
        if ($type == 'instructor'){
            $courses = DB::table('user_has_course')
                ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
                ->where('user_has_course.userid', '=', Auth::id())
                ->orwhere('user_has_course.userid', '=', $id)
                ->get();
            $courses = $courses->toArray();
            $tmp1 = array();
            foreach($courses as $key => $value) {
                if(!array_key_exists($value->coursecode,$tmp1)) {
                    $tmp1[$value->coursecode] = $value;
                    $tmp1[$value->coursecode]->who = '';
                } else {
                    $tmp1[$value->coursecode]->who = 'common';
                }
            }
            $courses = array_values($tmp1);
            foreach ($courses as $course){
                $course->type = 'course';
                $course->days = explode(',',$course->days);
                if ($course->userid == Auth::id() && $course->who != 'common'){
                    $course->who = 'me';
                } else {
                    if ($course->who != 'common'){
                        $course->who = 'user';
                    }
                }
            }
            $meetings = DB::table('user_has_meeting')
                ->join('meetings', 'user_has_meeting.meetingid', '=', 'meetings.id')
                ->where('user_has_meeting.userid', '=', Auth::id())
                ->where('meetings.status','=','accepted')
                ->where('user_has_meeting.status_meeting','=','accepted')
                ->orwhere('user_has_meeting.userid', '=', $id)
                ->where('meetings.status','=','accepted')
                ->where('user_has_meeting.status_meeting','=','accepted')
                ->get();
            $meetings = $meetings->toArray();
            $tmp2 = array();
            foreach($meetings as $key => $value) {
                if(!array_key_exists($value->meetingid,$tmp2)) {
                    $tmp2[$value->meetingid] = $value;
                    $tmp2[$value->meetingid]->who = '';
                } else {
                    $tmp2[$value->meetingid]->who = 'common';
                }
            }
            $meetings = array_values($tmp2);
            foreach ($meetings as $meeting){
                $meeting->type = 'meeting';
                $meeting->days = array($meeting->day);
                if (($meeting->userid == Auth::id() || intval($meeting->host) == Auth::id()) && $meeting->who != 'common'){
                    $meeting->who = 'me';
                } else {
                    if ($meeting->who != 'common'){
                        $meeting->who = 'user';
                    }
                }
            }
            $allevents = array_merge($courses,$meetings);
            return view('meetings.instructor_timetable',['id'=>$id,'allevents'=>$allevents,'monday'=>$monday,'tuesday'=>$tuesday,'wednesday'=>$wednesday,'thursday'=>$thursday,'friday'=>$friday])->render();
        }
        if ($type == 'group'){
            $group = $this->getGroupById($id);
            foreach ($group->members as $member){
                $results[] = DB::table('user_has_course')
                    ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
                    ->where('user_has_course.userid', '=', Auth::id())
                    ->orwhere('user_has_course.userid', '=', $member)
                    ->get()->toArray();
                $results2[] = DB::table('user_has_meeting')
                    ->join('meetings', 'user_has_meeting.meetingid', '=', 'meetings.id')
                    ->where('user_has_meeting.userid', '=', Auth::id())
                    ->where('meetings.status','=','accepted')
                    ->where('user_has_meeting.status_meeting','=','accepted')
                    ->orwhere('user_has_meeting.userid', '=', $member)
                    ->where('meetings.status','=','accepted')
                    ->where('user_has_meeting.status_meeting','=','accepted')
                    ->get()->toArray();
            }
            $courses = [];
            $meetings = [];
            foreach ($results as $result){
                foreach ($result as $value){
                    $courses[] = $value;
                }
            }
            foreach ($results2 as $result2){
                foreach ($result2 as $value2){
                    $meetings[] = $value2;
                }
            }
            $tmp1 = array();
            foreach($courses as $key => $value) {
                if(!array_key_exists($value->coursecode,$tmp1)) {
                    $tmp1[$value->coursecode] = $value;
                    $tmp1[$value->coursecode]->who = '';
                } else {
                    $tmp1[$value->coursecode]->who = 'common';
                }
            }
            $courses = array_values($tmp1);
            foreach ($courses as $course){
                $course->type = 'course';
                $course->days = explode(',',$course->days);
                if ($course->userid == Auth::id() && $course->who != 'common'){
                    $course->who = 'me';
                } else {
                    if ($course->who != 'common'){
                        $course->who = 'user';
                    }
                }
            }
            $tmp2 = array();
            foreach($meetings as $key => $value) {
                if(!array_key_exists($value->meetingid,$tmp2)) {
                    $tmp2[$value->meetingid] = $value;
                    $tmp2[$value->meetingid]->who = '';
                } else {
                    $tmp2[$value->meetingid]->who = 'common';
                }
            }
            $meetings = array_values($tmp2);
            foreach ($meetings as $meeting){
                $meeting->type = 'meeting';
                $meeting->days = array($meeting->day);
                if (($meeting->userid == Auth::id() || intval($meeting->host) == Auth::id()) && $meeting->who != 'common'){
                    $meeting->who = 'me';
                } else {
                    if ($meeting->who != 'common'){
                        $meeting->who = 'user';
                    }
                }
            }
            $allevents = array_merge($courses,$meetings);
            return view('meetings.group_timetable',['id'=>$id,'allevents'=>$allevents,'monday'=>$monday,'tuesday'=>$tuesday,'wednesday'=>$wednesday,'thursday'=>$thursday,'friday'=>$friday])->render();
        }
    }

    public function requests(){
        $requests = DB::table('meetings AS m')
            ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
            ->join('users AS u', 'u.id', '=', 'm.host')
            ->where('um.userid', '=', Auth::id())
            ->where('m.host', '!=', Auth::id())
            ->where('m.status', '=', 'pending')->paginate(10);
        $active = 'requests';
        return view('meetings.requests', compact('requests', 'active','requested'));
    }

    public function searchUser(Request $request){
        $query = $request->searchuser;
        $validation = Validator::make($request->all(),[
            'searchuser'=>'required'
        ]);
        if ($validation->fails()){
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }

        $groups = DB::table('groups')->where('name', 'LIKE', '%' . $query . '%')->paginate(10,['*'],'groups');
        foreach ($groups as $group){
            $groupinfo = $this->getGroupById($group->id);
            $group->admin = $this->getUserById($group->id_creator)->name;
            $group->members = $groupinfo->members;
            $group->userlist = $groupinfo->userlist;
        }

        $instructors = DB::table('users')->where('type', '=', 'teacher')->where(function ($q) use ($query){
            $q->where('users.id', '!=', Auth::id())->where('name', 'LIKE', '%' . $query . '%')->orwhere('campusid', 'LIKE', '%' . $query . '%')->where('users.id', '!=', Auth::id());
        })->paginate(10,['*'],'instructors');

        $users = DB::table('users')->where('type', '=', 'student')->where(function ($q) use ($query){
            $q->where('users.id', '!=', Auth::id())->where('name', 'LIKE', '%' . $query . '%')->orwhere('campusid', 'LIKE', '%' . $query . '%')->where('users.id', '!=', Auth::id());
        })->paginate(10,['*'],'users');

        return view('meetings.search_results',['groups'=>$groups,'instructors'=>$instructors,'users'=>$users,'query'=>$query])->render();
    }

    public function scheduleMeeting(){
        $active = 'meeting';
        return view('meetings.schedule',compact('active'));
    }

    public function q(Request $request)
    {
        $query = $request->input('query');
        $active = 'meeting';

        if (!$query) {
            $users = [];
            $instructors = [];
            $groups = [];
            return view('meetings.search', compact( 'instructors', 'users', 'groups', 'active'));
        }

        $groups = DB::table('groups')->where('name', 'LIKE', '%' . $query . '%')->paginate(10,['*'],'groups');
        foreach ($groups as $group){
            $groupinfo = $this->getGroupById($group->id);
            $group->admin = $this->getUserById($group->id_creator)->name;
            $group->members = $groupinfo->members;
            $group->userlist = $groupinfo->userlist;
        }

        $instructors = DB::table('users')->where('type', '=', 'teacher')->where(function ($q) use ($query){
            $q->where('users.id', '!=', Auth::id())->where('name', 'LIKE', '%' . $query . '%')->orwhere('campusid', 'LIKE', '%' . $query . '%')->where('users.id', '!=', Auth::id());
        })->paginate(10,['*'],'instructors');

        $users = DB::table('users')->where('type', '=', 'student')->where(function ($q) use ($query){
            $q->where('users.id', '!=', Auth::id())->where('name', 'LIKE', '%' . $query . '%')->orwhere('campusid', 'LIKE', '%' . $query . '%')->where('users.id', '!=', Auth::id());
        })->paginate(10,['*'],'users');

        return view('meetings.search', compact( 'instructors', 'users', 'groups', 'active','query'));
    }

    public function schedule(Request $request)
    {
        $start = $request->start;
        $duration = $request->duration;
        $date = $request->date;
        $userid = $request->id;
        $venue = $request->venue;
        $validation = Validator::make($request->all(),[
            'start'=>'required|after:09:00AM|before:06:00PM',
            'duration'=>'required|numeric|between:1,60',
            'date'=>'required|date|after:today',
            'venue'=>'required'
        ]);
        $end = date("H:i", strtotime('+'.$duration.' minutes', strtotime($start)));
        $day = date('l',strtotime($date));
        if ($validation->fails()){
            if ($day == 'Sunday' || $day == 'Saturday'){
                $validation->getMessageBag()->add('date','date cannot be a weekend');
            }
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }
        if ($day == 'Sunday' || $day == 'Saturday'){
            $validation->getMessageBag()->add('date','date cannot be a weekend');
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }
        $request = DB::table('meetings AS m')
            ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
            ->join('users AS u', 'u.id', '=', 'm.host')
            ->where('um.userid', '=', $userid)
            ->where('m.host', '=', Auth::id())
            ->where('m.date','=',$date)
            ->where('m.time','=',$start.'-'.$end)
            ->where('m.status', '=', 'pending')
            ->where('um.status_meeting','=','pending')
            ->first();
        if (count($request) > 0){
            return response()->json(['conflict'=>'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-times-circle"></i> You have already sent a pending meeting request to this user at the suggested slot. Try another slot</div>']);
        }
        $courses = DB::table('user_has_course')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('user_has_course.userid', '=', Auth::id())
            ->orwhere('user_has_course.userid', '=', $userid)
            ->get();
        $courses = $courses->toArray();
        $tmp1 = array();
        foreach($courses as $key => $value) {
            if(!array_key_exists($value->coursecode,$tmp1)) {
                $tmp1[$value->coursecode] = $value;
            }
        }
        $courses = array_values($tmp1);
        foreach ($courses as $course){
            $course->days = explode(',',$course->days);
            $course->starttime = explode('-',$course->timing)[0];
            $course->endtime = explode('-',$course->timing)[1];
            $course->type = 'course';
        }
        $meetings = DB::table('user_has_meeting')
            ->join('meetings', 'user_has_meeting.meetingid', '=', 'meetings.id')
            ->where('user_has_meeting.userid', '=', Auth::id())
            ->where('meetings.status','=','accepted')
            ->where('user_has_meeting.status_meeting','=','accepted')
            ->orwhere('user_has_meeting.userid', '=', $userid)
            ->where('meetings.status','=','accepted')
            ->where('user_has_meeting.status_meeting','=','accepted')
            ->get();
        $meetings = $meetings->toArray();
        $tmp2 = array();
        foreach($meetings as $key => $value) {
            if(!array_key_exists($value->meetingid,$tmp2)) {
                $tmp2[$value->meetingid] = $value;
            }
        }
        $meetings = array_values($tmp2);
        foreach ($meetings as $meeting){
            $meeting->days = array($meeting->day);
            $meeting->starttime = date('H:i',strtotime(explode('-',$meeting->time)[0]));
            $meeting->endtime = date('H:i',strtotime(explode('-',$meeting->time)[1]));
            $meeting->type = 'meeting';
        }
        $allevents = array_merge($courses,$meetings);
        foreach ($allevents as $event){
            if ($event->type == 'course'){
                foreach ($event->days as $eventday){
                    if ($eventday == $day){
                        if (($start >= $event->starttime && $start <= $event->endtime) || ($end >= $event->starttime && $end <= $event->endtime)){
                            return response()->json(['conflict'=>'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-times-circle"></i> Conflict detected! Please select another slot</div>']);
                        }
                    } else {
                        continue;
                    }
                }
            } else {
                foreach ($event->days as $eventday){
                    if ($eventday == $day && $event->date == date('Y-m-d',strtotime(str_replace("/", "-", $event->date)))){
                        if (($start >= $event->starttime && $start <= $event->endtime) || ($end >= $event->starttime && $end <= $event->endtime)){
                            return response()->json(['conflict'=>'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-times-circle"></i> Conflict detected! Please select another slot</div>']);
                        }
                    } else {
                        continue;
                    }
                }
            }
        }
        $time = $start.'-'.$end;
        $insert = DB::table('meetings')->insertGetId(['time'=>strval($time), 'venue'=>strval($venue) , 'day'=>strval($day), 'date'=>strval($date), 'host'=>strval(Auth::id()), 'status' => 'pending']);
        DB::table('user_has_meeting')->insert(array('userid'=>Auth::id(), 'meetingid'=>$insert,'status_meeting'=>'accepted'));
        DB::table('user_has_meeting')->insert(array('userid'=>$userid, 'meetingid'=>$insert));

        $notificationList = ','.$userid.',';
        $loggedIn = $this->getUserById(Auth::id());
        $html = '<span class="label label-info">'.$loggedIn->name . ' ('.$loggedIn->campusid.')</span> has requested to meet you on <span class="label label-info">'.date('F d,Y',strtotime($date)).'</span> at <span class="label label-info">'.date('h:iA',strtotime(explode('-',$time)[0])).' - '.date('h:iA',strtotime(explode('-',$time)[1])).'</span>';
        DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'meeting-request', 'userlist' => $notificationList));

        return response()->json(['success'=>'<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-check-circle"></i> Request sent successfully and the user notified!</div>']);
    }

    public function scheduleGroupMeeting(Request $request){
        $start = $request->start;
        $duration = $request->duration;
        $date = $request->date;
        $groupid = $request->id;
        $venue = $request->venue;
        $validation = Validator::make($request->all(),[
            'start'=>'required|after:09:00AM|before:06:00PM',
            'duration'=>'required|numeric|between:1,60',
            'date'=>'required|date|after:today',
            'venue'=>'required'
        ]);
        $end = date("H:i", strtotime('+'.$duration.' minutes', strtotime($start)));
        $day = date('l',strtotime($date));
        if ($validation->fails()){
            if ($day == 'Sunday' || $day == 'Saturday'){
                $validation->getMessageBag()->add('date','date cannot be a weekend');
            }
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }
        if ($day == 'Sunday' || $day == 'Saturday'){
            $validation->getMessageBag()->add('date','date cannot be a weekend');
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }
        $group = $this->getGroupById($groupid);
        foreach ($group->members as $member){
            $request = DB::table('meetings AS m')
                ->join('user_has_meeting as um', 'm.id', '=', 'um.meetingid')
                ->join('users AS u', 'u.id', '=', 'm.host')
                ->where('um.userid', '=', $member)
                ->where('m.host', '=', Auth::id())
                ->where('m.date','=',$date)
                ->where('m.time','=',$start.'-'.$end)
                ->where('m.status', '=', 'pending')
                ->where('um.status_meeting','=','pending')
                ->first();
            if (count($request) > 0){
                return response()->json(['conflict'=>'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-times-circle"></i> You have already sent a pending meeting request to this group at the suggested slot. Try another slot</div>']);
            }
            $results[] = DB::table('user_has_course')
                ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
                ->where('user_has_course.userid', '=', Auth::id())
                ->orwhere('user_has_course.userid', '=', $member)
                ->get()->toArray();
            $results2[] = DB::table('user_has_meeting')
                ->join('meetings', 'user_has_meeting.meetingid', '=', 'meetings.id')
                ->where('user_has_meeting.userid', '=', Auth::id())
                ->where('meetings.status','=','accepted')
                ->where('user_has_meeting.status_meeting','=','accepted')
                ->orwhere('user_has_meeting.userid', '=', $member)
                ->where('meetings.status','=','accepted')
                ->where('user_has_meeting.status_meeting','=','accepted')
                ->get()->toArray();
        }
        $courses = [];
        $meetings = [];
        foreach ($results as $result){
            foreach ($result as $value){
                $courses[] = $value;
            }
        }
        foreach ($results2 as $result2){
            foreach ($result2 as $value2){
                $meetings[] = $value2;
            }
        }
        $tmp1 = array();
        foreach($courses as $key => $value) {
            if(!array_key_exists($value->coursecode,$tmp1)) {
                $tmp1[$value->coursecode] = $value;
            }
        }
        $courses = array_values($tmp1);
        foreach ($courses as $course){
            $course->type = 'course';
            $course->starttime = explode('-',$course->timing)[0];
            $course->endtime = explode('-',$course->timing)[1];
            $course->days = explode(',',$course->days);
        }
        $tmp2 = array();
        foreach($meetings as $key => $value) {
            if(!array_key_exists($value->meetingid,$tmp2)) {
                $tmp2[$value->meetingid] = $value;
            }
        }
        $meetings = array_values($tmp2);
        foreach ($meetings as $meeting){
            $meeting->type = 'meeting';
            $meeting->starttime = date('H:i',strtotime(explode('-',$meeting->time)[0]));
            $meeting->endtime = date('H:i',strtotime(explode('-',$meeting->time)[1]));
            $meeting->days = array($meeting->day);
        }
        $allevents = array_merge($courses,$meetings);
        foreach ($allevents as $event){
            if ($event->type == 'course'){
                foreach ($event->days as $eventday){
                    if ($eventday == $day){
                        if (($start >= $event->starttime && $start <= $event->endtime) || ($end >= $event->starttime && $end <= $event->endtime)){
                            return response()->json(['conflict'=>'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-times-circle"></i> Conflict detected! Please select another slot</div>']);
                        }
                    } else {
                        continue;
                    }
                }
            } else {
                foreach ($event->days as $eventday){
                    if ($eventday == $day && $event->date == date('Y-m-d',strtotime(str_replace("/", "-", $event->date)))){
                        if (($start >= $event->starttime && $start <= $event->endtime) || ($end >= $event->starttime && $end <= $event->endtime)){
                            return response()->json(['conflict'=>'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-times-circle"></i> Conflict detected! Please select another slot</div>']);
                        }
                    } else {
                        continue;
                    }
                }
            }
        }
        $time = $start.'-'.$end;
        $insert = DB::table('meetings')->insertGetId(['time'=>strval($time), 'venue'=>strval($venue), 'day'=>strval($day), 'date'=>strval($date), 'host'=>strval(Auth::id()), 'status' => 'pending', 'type'=>'group']);
        foreach ($group->members as $memberid){
            if ($memberid == Auth::id()){
                DB::table('user_has_meeting')->insert(array('userid'=>$memberid, 'meetingid'=>$insert,'status_meeting'=>'accepted'));
            } else {
                DB::table('user_has_meeting')->insert(array('userid'=>$memberid, 'meetingid'=>$insert));
            }
            if ($memberid != Auth::id()){
                $notificationList = ','.$memberid.',';
                $loggedIn = $this->getUserById(Auth::id());
                $html = '<span class="label label-info">'.$loggedIn->name . ' ('.$loggedIn->campusid.')</span> has requested to meet you on <span class="label label-info">'.date('F d,Y',strtotime($date)).'</span> at <span class="label label-info">'.date('h:iA',strtotime(explode('-',$time)[0])).' - '.date('h:iA',strtotime(explode('-',$time)[1])).'</span>';
                DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'meeting-request', 'userlist' => $notificationList));
            }
        }

        return response()->json(['success'=>'<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-check-circle"></i> Request sent successfully and the user notified!</div>']);
    }

    public function accept(Request $request) {
        $meetingid = $request->meetingid;
        $meeting = $this->getMeetingById($meetingid);
        DB::table('user_has_meeting')
            ->where('userid','=',Auth::id())
            ->where('meetingid','=',$meetingid)
            ->update(['status_meeting'=>'accepted']);
        DB::table('meetings')->where('id','=',$meetingid)->update(['status'=>'accepted']);
        $notificationList = ','.$meeting->host.',';
        $loggedIn = $this->getUserById(Auth::id());
        $html = '<span class="label label-info">'.$loggedIn->name . ' ('.$loggedIn->campusid.')</span> has accepted your request to meet on <span class="label label-info">'.date('F d,Y',strtotime($meeting->date)).'</span> at <span class="label label-info">'.date('h:iA',strtotime(explode('-',$meeting->time)[0])).' - '.date('h:iA',strtotime(explode('-',$meeting->time)[1])).'</span>';
        DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'meeting', 'userlist' => $notificationList));
        $request->session()->flash('message','Meeting Confirmed! You can now see it under your scheduled meetings');
        return;
    }

    public function reject(Request $request) {
        $validation = Validator::make($request->all(), [
            'reason'=>'required'
        ]);
        if ($validation->fails()){
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }
        $meetingid = $request->meetingid;
        $meeting = $this->getMeetingById($meetingid);
        $message = $request->reason;
        if ($meeting->type == 'group'){
            DB::table('user_has_meeting')
                ->where('userid','=',Auth::id())
                ->where('meetingid','=',$meetingid)
                ->update(['status_meeting'=>'rejected','message'=>$message]);
        } else {
            DB::table('meetings')->where('id', '=', $meetingid)
                ->update([
                    'status' => 'rejected',
                    'message' => $message
                ]);
        }
        $loggedIn = $this->getUserById(Auth::id());
        $notificationList = ','.$meeting->host.',';
        $html = '<span class="label label-info">'.$loggedIn->name . ' ('.$loggedIn->campusid.')</span>'. ' has rejected your request to meet on <span class="label label-info">'.date('F d,Y',strtotime($meeting->date)).'</span> at <span class="label label-info">'.date('h:iA',strtotime(explode('-',$meeting->time)[0])).' - '.date('h:iA',strtotime(explode('-',$meeting->time)[1])).'. </span><span class="label label-default">Reason: '.$message.'</span>';
        DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'meeting', 'userlist' => $notificationList));
        $request->session()->flash('message','Meeting Rejected! Others will be notified about it');
        return;
    }
}
