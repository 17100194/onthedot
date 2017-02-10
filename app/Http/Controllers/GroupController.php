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

class GroupController
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