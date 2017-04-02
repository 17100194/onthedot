<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/29/2017
 * Time: 4:42 PM
 */

namespace App\Http\Controllers;

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
        $sql = "SELECT u.id, u.name, u.campusid FROM users u
		WHERE u.name LIKE '%".$request->term."%' AND u.ID != ".Auth::id()."
		LIMIT 10";

        $result = DB::select($sql);
        if ($result) {
            foreach($result as $row) {
                $json[] = ['id'=>$row->id, 'text'=>$row->name ." - ". $row->campusid];
            }
            echo json_encode($json);
            return;
        }
        return json_encode([]);

    }

    public function all(){
        $groups = DB::table('groups')
            ->join('user_has_group', 'user_has_group.id_group', '=', 'groups.id')
            ->join('users as u2', 'u2.id', '=', 'groups.id_creator')
            ->where('user_has_group.id_user', '=', Auth::id())
            ->select('groups.name as groupname', 'groups.id', 'u2.name as creator', 'groups.created_on', 'groups.id_creator')
            ->orderby('groups.created_on', 'DESC')
            ->get();
        foreach ($groups as $group){
            $groupMembers = DB::table('user_has_group')->where('id_group', '=', $group->id)->select('id_user')->get();
            if (count($groupMembers) > 0) {
                foreach ($groupMembers as $groupMember) {
                    $group->members[] = $this->getUserById($groupMember->id_user);
                }
            }
        }
        $active = 'mygroups';
        return view('group.all', compact('groups', 'active'));
    }

    public function sendGroupRequest(Request $request){
        $users = $request->ids;
        $groupid = $request->groupid;
        $group = $this->getGroupById($groupid);
        foreach($users as $user) {
            $insert = DB::table('user_has_group_request')->insertGetId(array(
                'id_group'=>$groupid,
                'id_sender' => Auth::id(),
                'id_receiver'=>$user,
                'status'=>'pending'));
            $notificationList = ','.$user.',';
            $loggedIn = $this->getUserById(Auth::id());
            $html = '<a href="'.url('/notification?type=group-pending&id='. strval($insert)).'"><strong>'.$loggedIn->name . ' ('.$loggedIn->campusid.')</strong>'. ' has requested you to join their group <strong>'.$group->name.'</strong></a>';
            DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'group', 'userlist' => $notificationList));
        }

        return 'success';
    }

    public function removeMember(Request $request){
        $userid = $request->userid;
        $groupid = $request->groupid;
        DB::table('user_has_group')->where('id_user', '=', $userid)->where('id_group', '=', $groupid)->delete();
        $loggedIn = $this->getUserById(Auth::id());
        $group = $this->getGroupById($groupid);
        $txt = '<strong>'.$loggedIn->name.'</strong> has removed you from the group'.$group->name;
        DB::table('user_notifications')->insert(array('notification_content' => $txt, 'type'=>'group', 'userlist'=> ','.$userid.','));
        return 'success';
    }

    public function leaveGroup(Request $request) {
        $groupid = $request->groupid;
        $adminid = $request->adminid;
        if($adminid == 'empty'){
            DB::table('user_has_group')->where('id_user', '=', Auth::id())->where('id_group', '=', intval($groupid))->delete();
            $group = $this->getGroupById($groupid);
            if (count($group->members) > 0){
                $notificationList = implode(',', $group->members);
                $loggedIn = $this->getUserById(Auth::id());
                $txt = '<strong>'.$loggedIn->name.' (' . $loggedIn->campusid . ')</strong> has left the group <strong>'. $group->name .'</strong>';
                DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'group', 'userlist' => ','.$notificationList.','));
                session(['message' => 'Group Left Successfully!']);
                return;
            } else {
                DB::table('groups')->where('id', '=', $groupid)->delete();
                session(['message' => 'Group Left Successfully!']);
                return;
            }
        }
        DB::table('user_has_group')->where('id_user', '=', Auth::id())->where('id_group', '=', intval($groupid))->delete();
        DB::table('groups')->where('id', '=', $groupid)->update(['id_creator' => $adminid]);
        // get others notification that you left group

        $group = $this->getGroupById($groupid);

        if (count($group->members) > 0) {
            $notificationList = ','.implode(',', $group->members).',';
            $loggedIn = $this->getUserById(Auth::id());
            $txt = '<strong>'.$loggedIn->name.' (' . $loggedIn->campusid . ')</strong> has left the group <strong>'. $group->name .'</strong>';
            DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'group', 'userlist' => ','.$notificationList.','));
        }
        session(['message' => 'Group Left Successfully!']);
        return;
    }

    public function acceptRequest(Request $request) {
        $requestid = $request->requestid;
        DB::table('user_has_group_request')->where('id', '=', $requestid)
            ->update(['status' => 'accepted']);

        $groupid = DB::table('user_has_group_request')->where('id', $requestid)->get()[0]->id_group;
        $group = $this->getGroupById($groupid);
        $notificationList = ','.$group->idcreator.',';
        $loggedIn = $this->getUserById(Auth::id());
        $txt = '<strong>'.$loggedIn->name.' (' . $loggedIn->campusid . ')</strong> has accepted the request for group <strong>'.$group->name .'</strong>';
        DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'group', 'userlist' => $notificationList));

        DB::table('user_has_group')->insert(array(
            'id_user'=>Auth::id(),
            'id_group'=>$groupid));

    }

    public function rejectRequest(Request $request) {
        $requestid = $request->requestid;
        DB::table('user_has_group_request')->where('id', '=', $requestid)
            ->update([
                'status' => 'rejected'
            ]);



        $group = $this->getGroupByRequestId($requestid);
        $notificationList = ','.$group->idcreator.',';
        $loggedIn = $this->getUserById(Auth::id());
        $txt = '<strong>'.$loggedIn->name.' (' . $loggedIn->campusid . ')</strong> has rejected the request for group <strong>'.$group->name .'</strong>';
        DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'group', 'userlist' => $notificationList));

    }

    public function makegroup(Request $request){
        $users = $request->ids;
        $groupname = $request->groupname;
        $groupid = DB::table('groups')->insertGetId(array(
            'name'=>$groupname,
            'id_creator' => Auth::id()));

        DB::table('user_has_group')->insert(array(
            'id_user'=>Auth::id(),
            'id_group' => $groupid));

        foreach($users as $user) {
            $insert = DB::table('user_has_group_request')->insertGetId(array(
                'id_group'=>$groupid,
                'id_sender' => Auth::id(),
                'id_receiver'=>$user,
                'status'=>'pending'));

            $notificationList = ','.strval($user).',';
            $loggedIn = $this->getUserById(Auth::id());
            $url = url("/notification?type=group-pending&id=". strval($insert));
            $html = '<a href="'.$url.'"><strong>'.$loggedIn->name . ' ('.$loggedIn->campusid.')</strong> has requested you to join their group <strong>'.$groupname.'</strong></a>';
            DB::table('user_notifications')->insert(array('notification_content'=> $html, 'type'=>'group', 'userlist' => $notificationList));

        }
        echo 'success';
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
//                ->where('users.id', '!=', Auth::id())
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