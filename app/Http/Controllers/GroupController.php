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
        foreach($users as $user) {
            DB::table('user_has_group_request')->insert(array(
                'id_group'=>$groupid,
                'id_sender' => Auth::id(),
                'id_receiver'=>$user,
                'status'=>'pending'));
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
        DB::table('user_notifications')->insert(array('notification_content' => $txt, 'type'=>'group', 'userlist'=> $userid));
        return 'success';
    }

    public function leaveGroup(Request $request) {
        $groupid = $request->groupid;
        $adminid = $request->adminid;
        DB::table('user_has_group')->where('id_user', '=', Auth::id())->where('id_group', '=', intval($groupid))->delete();
        DB::table('groups')->where('id', '=', $groupid)->update(['id_creator' => $adminid]);
        // get others notification that you left group

        $group = $this->getGroupById($groupid);

        if (count($group->members) > 0) {
            $notificationList = implode(',', $group->members);
            $loggedIn = $this->getUserById(Auth::id());
            $txt = '<strong>'.$loggedIn->name.' (' . $loggedIn->campusid . ')</strong> has left the group <strong>'. $group->name .'</strong>';
            DB::table('user_notifications')->insert(array('notification_content'=> $txt, 'type'=>'group', 'userlist' => $notificationList));
        }

        session(['message' => 'Group Left Successfully']);
        return 'success';
    }

    public function acceptRequest(Request $request) {
        $requestid = $request->requestid;
        DB::table('user_has_group_request')->where('id', '=', $requestid)
            ->update(['status' => 'accepted']);

        $groupid = DB::table('user_has_group_request')->where('id', $requestid)
            ->value('id_group');

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
            DB::table('user_has_group_request')->insert(array(
                'id_group'=>$groupid,
                'id_sender' => Auth::id(),
                'id_receiver'=>$user,
                'status'=>'pending'));
        }
        echo 'success';
    }
}