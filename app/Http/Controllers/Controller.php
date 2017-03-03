<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getUserById($userId) {
        return DB::table('users')
            ->where('id', '=', $userId)
            ->select('id', 'name', 'campusid', 'type', 'created_at')
            ->get()[0];
    }

    public function getGroupById($groupId) {
        $sql = DB::table('groups')
            ->join('user_has_group', 'user_has_group.id_group', 'groups.id')
            ->where('groups.id', '=', $groupId)
            ->get();

        $app = app();
        $groupInfo = $app->make('stdClass');
        $groupUsers = array();
        $first = true;
        $groupInfo->members = array();
        foreach ($sql as $group) {
            if ($first) {
                $groupInfo->id = $group->id;
                $groupInfo->name = $group->name;
                $groupInfo->idcreator = $group->id_creator;
                $groupInfo->created_on = $group->created_on;
                $groupInfo->id = $this->getUserById($group->id_creator);
            }
            $groupUsers[] = $group->id_user;
            $first = false;
        }
//        var_dump($groupInfo);
        if (count($groupUsers) > 0) {
            $groupInfo->members = $groupUsers;
        }

        return $groupInfo;

    }

    public function getMeetingById($idMeeting) {
        return DB::table('meetings')
            ->where('id', '=', $idMeeting)->get()[0];
    }
}
