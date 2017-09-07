<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/29/2017
 * Time: 4:42 PM
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class GroupController extends Controller
{
    public $timeTableStart = '08:00';
    public $timeTableEnd = '18:00';
    public $tableHeight = 550;
    public $timeTableWidth = 155;

    public function createForm(){
        $active = 'group';
        return view('group.create', compact('active'));
    }

    public function searchuser(Request $request){
        if($request->term == ""){
            return json_encode([]);
        }
        $sql = "SELECT u.type, u.id, u.name, u.campusid FROM users u
		WHERE u.name LIKE '%".$request->term."%' AND u.ID != ".Auth::id()." AND u.verified = 1
		LIMIT 10";

        $result = DB::select($sql);
        if ($result) {
            $json = [];
            foreach($result as $row) {
                if ($request->groupid){
                    $hasgroup = DB::table('user_has_group')->where('id_user', '=', $row->id)->where('id_group', '=', $request->groupid)->first();
                    if (is_null($hasgroup)){
                        $json[] = ['id'=>$row->id, 'text'=>$row->name ." - ". $row->campusid];
                    } else {
                        continue;
                    }
                } else {
                    $json[] = ['id'=>$row->id, 'text'=>$row->name ." - ". $row->campusid];
                }
            }
            return json_encode($json);
        }
        return json_encode([]);

    }

    public function requests(){
        $requests = DB::table('user_has_group_request')
            ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
            ->join('users', 'users.id', '=', 'user_has_group_request.id_sender')
            ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'groups.created_on', 'user_has_group_request.id as requestid')
            ->where('id_receiver', '=', Auth::id())
            ->where('status', '=', 'pending')
            ->orderBy('created_on','desc')
            ->paginate(10);
        $active = 'group-requests';
        return view('group.requests',compact('requests','active'));
    }

    public function requestDetails(Request $request){
        $group = $this->getGroupByRequestId($request->requestid);
        return view('group.request_details',['group'=>$group,'requestid'=>$request->requestid])->render();
    }

    public function showGroupMembers(Request $request){
        $groupmembers = DB::table('user_has_group')
            ->join('users', 'users.id', 'user_has_group.id_user')
            ->where('user_has_group.id_group', '=', $request->groupid)
            ->where('users.id', '!=', Auth::id())
            ->select('users.name as name', 'users.id as id', 'users.campusid as campusid', 'users.type as type')
            ->paginate(3);
        return view('group.group_members')->with(['groupmembers' => $groupmembers, 'idcreator'=>$request->idcreator, 'groupid'=>$request->groupid])->render();
    }

    public function groupDetails(Request $request){
        $group = DB::table('groups')
            ->join('users', 'users.id', 'groups.id_creator')
            ->where('groups.id', '=', $request->groupid)
            ->select('groups.id as id', 'groups.name as name', 'users.name as creator', 'groups.created_on as created_on', 'groups.id_creator as id_creator')
            ->first();
        return view('group.group_details')->with(['group' => $group])->render();
    }

    public function all(){
        $groups = DB::table('groups')
            ->join('user_has_group', 'user_has_group.id_group', '=', 'groups.id')
            ->join('users as u2', 'u2.id', '=', 'groups.id_creator')
            ->where('user_has_group.id_user', '=', Auth::id())
            ->select('groups.name as groupname', 'groups.id', 'u2.name as creator', 'groups.created_on', 'groups.id_creator')
            ->orderby('groups.created_on', 'DESC')
            ->paginate(10);
        $active = 'mygroups';
        return view('group.all', compact('groups', 'active'));
    }

    public function sendGroupRequest(Request $request){
        $users = $request->ids;
        if (empty($users)){
            return 'error';
        }
        $groupid = $request->groupid;
        $group = $this->getGroupById($groupid);
        foreach($users as $user) {
            $request = DB::table('user_has_group_request')
                ->join('groups', 'user_has_group_request.id_group', '=', 'groups.id')
                ->join('users', 'users.id', '=', 'user_has_group_request.id_sender')
                ->select('users.name AS username', 'groups.name as groupname', 'users.campusid', 'groups.created_on', 'user_has_group_request.id as requestid')
                ->where('id_receiver', '=', $user)
                ->where('groups.id','=',$groupid)
                ->where('status', '=', 'pending')
                ->first();
            if (count($request) > 0){
                return '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <i class="fa fa-times-circle"></i> You have already sent a pending request to atleast one of the users selected.
                </div>';
            }
            $insert = DB::table('user_has_group_request')->insertGetId(array(
                'id_group'=>$groupid,
                'id_sender' => Auth::id(),
                'id_receiver'=>$user,
                'status'=>'pending','created_on'=>Carbon::now()));
            $notificationList = $user;
            $loggedIn = $this->getUserById(Auth::id());
            $html = '<span class="label label-info">'.$loggedIn->name . ' ('.$loggedIn->campusid.')</span>'. ' has requested you to join their group <span class="label label-info">'.$group->name.'</span>';
            DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'group-request', 'userid' => $notificationList, 'created_on'=>Carbon::now()));
        }
        return '<div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <i class="fa fa-check-circle"></i> Request sent to all the users added!
                </div>';
    }

    public function removeMember(Request $request){
        $userid = $request->userid;
        $groupid = $request->groupid;
        DB::table('user_has_group')->where('id_user', '=', $userid)->where('id_group', '=', $groupid)->delete();
        $loggedIn = $this->getUserById(Auth::id());
        $group = $this->getGroupById($groupid);
        $txt = '<span class="label label-info">'.$loggedIn->name.'</span> has removed you from the group <span class="label label-info">'.$group->name.'</span>';
        DB::table('user_notifications')->insert(array('notification_content' => $txt, 'type'=>'group', 'userid'=> $userid,'created_on'=>Carbon::now()));
        return 'success';
    }

    public function leaveGroup(Request $request) {
        $groupid = $request->groupid;
        $adminid = $request->adminid;
        if ($adminid == 'none'){
            return '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                    <i class="fa fa-times-circle"></i> You must select an admin
                    </div>';
        }
        if($adminid == 'empty'){
            DB::table('user_has_group')->where('id_user', '=', Auth::id())->where('id_group', '=', intval($groupid))->delete();
            $group = $this->getGroupById($groupid);
            if (count($group->members) > 0){
                $loggedIn = $this->getUserById(Auth::id());
                $txt = '<span class="label label-info">'.$loggedIn->name.' (' . $loggedIn->campusid . ')</span> has left the group <span class="label label-info">'. $group->name .'</span>';
                foreach ($group->members as $member){
                    DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'group', 'userid' => $member,'created_on'=>Carbon::now()));
                }
                session(['message' => 'You have left the group successfully!']);
                return 'success';
            } else {
                DB::table('groups')->where('id', '=', $groupid)->delete();
                session(['message' => 'Group Deleted Successfully!']);
                return 'success';
            }
        }
        DB::table('user_has_group')->where('id_user', '=', Auth::id())->where('id_group', '=', intval($groupid))->delete();
        DB::table('groups')->where('id', '=', $groupid)->update(['id_creator' => $adminid]);
        $group = $this->getGroupById($groupid);

        if (count($group->members) > 0) {
            $loggedIn = $this->getUserById(Auth::id());
            $txt = '<span class="label label-info">'.$loggedIn->name.' (' . $loggedIn->campusid . ')</span> has left the group <span class="label label-info">'. $group->name .'</span>';
            foreach ($group->members as $member){
                DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'group', 'userid' => $member,'created_on'=>Carbon::now()));
            }
        }
        session(['message' => 'You have left the group successfully!']);
        return 'success';
    }

    public function acceptRequest(Request $request) {
        $requestid = $request->requestid;
        DB::table('user_has_group_request')->where('id', '=', $requestid)
            ->update(['status' => 'accepted']);

        $groupid = DB::table('user_has_group_request')->where('id', $requestid)->get()[0]->id_group;
        $group = $this->getGroupById($groupid);
        $notificationList = $group->idcreator;
        $loggedIn = $this->getUserById(Auth::id());
        $txt = '<span class="label label-info">'.$loggedIn->name.' (' . $loggedIn->campusid . ')</span> has accepted the request to join your group <span class="label label-info">'.$group->name .'</span>';
        DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'group', 'userid' => $notificationList,'created_on'=>Carbon::now()));

        DB::table('user_has_group')->insert(array(
            'id_user'=>Auth::id(),
            'id_group'=>$groupid));
        $request->session()->flash('message','Request Accepted! The user will be notified');
        return;
    }

    public function rejectRequest(Request $request) {
        $requestid = $request->requestid;
        DB::table('user_has_group_request')->where('id', '=', $requestid)
            ->update([
                'status' => 'rejected'
            ]);
        $group = $this->getGroupByRequestId($requestid);
        $notificationList = $group->idcreator;
        $loggedIn = $this->getUserById(Auth::id());
        $txt = '<span class="label label-info">'.$loggedIn->name.' (' . $loggedIn->campusid . ')</span> has rejected the request to join your group <span class="label label-info">'.$group->name .'</span>';
        DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'group', 'userid' => $notificationList,'created_on'=>Carbon::now()));
        $request->session()->flash('message','Request Rejected! The user will be notified');
        return;
    }

    public function makegroup(Request $request){
        $users = $request->members;
        $groupname = $request->group_name;
        $validation = Validator::make($request->all(), ['group_name' => 'required', 'members' => 'required']);
        if ($validation->fails())
        {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $groupid = DB::table('groups')->insertGetId(array(
            'name'=>$groupname,
            'id_creator' => Auth::id(),'created_on'=>Carbon::now()));

        DB::table('user_has_group')->insert(array(
            'id_user'=>Auth::id(),
            'id_group' => $groupid));

        foreach($users as $user) {
            $insert = DB::table('user_has_group_request')->insertGetId(array(
                'id_group'=>$groupid,
                'id_sender' => Auth::id(),
                'id_receiver'=>$user,
                'status'=>'pending','created_on'=>Carbon::now()));

            $loggedIn = $this->getUserById(Auth::id());
            $url = url("/notification?type=group-pending&id=". strval($insert));
            $html = '<span class="label label-info">'.$loggedIn->name . ' ('.$loggedIn->campusid.')</span> has requested you to join their group <span class="label label-info">'.$groupname.'</span>';
            DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'group-request', 'userid' => $user, 'created_on'=>Carbon::now()));

        }
        return redirect()->back()->with('message', '<strong>Group created successfully!</strong>Requests to join the group have been sent to all the members.');
    }


    public function getGroupTimetable(Request $request, $id) {
        $idGroup = $id;
        $group = $this->getGroupById($idGroup);
        $aggregate = [];
        foreach($group->members as $user) {
            $userCourses = DB::table('users')
                ->join('user_has_course', 'users.id', '=', 'user_has_course.userid')
                ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
                ->where('users.id', '=', $user)
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
        $courses = $aggregate;
        $url = url('/scheduleGroup');
        return view('group.schedule', compact('courses', 'idGroup', 'url'));
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

}