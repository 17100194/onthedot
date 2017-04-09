<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DataTime;
use DataTimeZone;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Returns user details by ID user
     *
     * @param $userId
     * @return Array of Objects
     */
    public function getUserById($userId) {
        return DB::table('users')
            ->where('id', '=', $userId)
            ->select('id', 'name', 'campusid', 'type', 'created_at')
            ->get()[0];
    }

    /**
     * Returns group details by ID group
     *
     * @param $groupId
     * @return Array of Objects
     */
    public function getGroupById($groupId) {
        $sql = DB::table('groups')
            ->join('user_has_group', 'user_has_group.id_group', 'groups.id')
            ->where('groups.id', '=', $groupId)
            ->get();
        $app = app();
        $groupInfo = $app->make('stdClass');
        $groupUsers = array();
        $first = true;
        $groupMembers = [];
        $groupInfo->members = array();
        foreach ($sql as $group) {
            if ($first) {
                $groupInfo->id = $group->id;
                $groupInfo->name = $group->name;
                $groupInfo->idcreator = $group->id_creator;
                $groupInfo->created_on = $group->created_on;
                $groupInfo->creator = $this->getUserById($group->id_creator);
            }
            $groupUsers[] = $group->id_user;
            $groupMembers[] = $this->getUserById($group->id_user);
            $first = false;
        }
//        var_dump($groupInfo);
        if (count($groupUsers) > 0) {
            $groupInfo->members = $groupUsers;
            $groupInfo->userlist = $groupMembers;
        }

        return $groupInfo;

    }

    /**
     * Returns the details of a meeting by it'd ID
     *
     * @param $idMeeting
     * @return Array of Objects
     */
    public function getMeetingById($idMeeting) {
        return DB::table('meetings')
            ->where('id', '=', $idMeeting)->get()[0];
    }

    /**
     * Returns meetings for the provided user ID
     * @param $idUser
     * @return Array of Objects
     */
    public function getUserMeetings($idUser) {
        return DB::table('user_has_meeting AS u1')
            ->join('user_has_meeting as u2', 'u1.meetingid', '=', 'u2.meetingid')
            ->join('users', 'u2.userid', '=', 'users.id')
            ->join('meetings', 'u2.meetingid', '=', 'meetings.id')
            ->where('meetings.status', '=', 'accepted')
            ->where('u1.userid', '=', $idUser)
            ->where('u2.userid', '!=', $idUser)
            ->select('u2.meetingid', 'host', 'time', 'date', 'day', 'status', 'message', 'meetings.created_on')->get();
    }

    public function getGroupByRequestId($idRequest) {
        $idgroup = DB::table('user_has_group_request')->where('id', '=', $idRequest)->get()[0]->id_group;
        return $this->getGroupById($idgroup);
    }

    /**
     * Returns courses for the specific userID
     * @param $idUser
     * @return mixed
     */
    public function getUserCourses($idUser) {
        return DB::table('user_has_course')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('user_has_course.userid', '=', $idUser)->get();
    }
}
