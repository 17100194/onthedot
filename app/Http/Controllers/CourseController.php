<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/8/2017
 * Time: 5:18 PM
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CourseController
{
    public function makeform()
    {
        return view('course.make');
    }

    public function enroll(){
        return view('course.enroll');
    }

    public function enrollcourse(Request $request){
        $courseid = $request->course_id;
        $userid = Auth::id();
        if ($this->userHasCourse($userid, $courseid)) {
            return 'error';
        } else {
            DB::table('user_has_course')->insert(array(
                'userid'=> $userid,
                'courseid' => intval($courseid)));
        }

        return 'success';
    }

    public function userHasCourse($userId, $courseId) {
        $course = DB::table('user_has_course')
            ->where('userid', '=', $userId)
            ->where('courseid', '=', $courseId)->get();

        if (count($course) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function searchcourse(Request $request)
    {
        $name = $request->search_term;

        $courses = DB::table('courses AS c')
            ->join('users as u', 'u.id', '=', 'c.instructorid')
            ->where('c.name', 'LIKE', '%'.$name.'%')
            ->select('u.name AS username', 'u.campusid', 'u.type', 'c.*')->get();

        foreach ($courses as $course) {
            if ($this->userHasCourse(Auth::id(), $course->courseid)) {
                $course->enrolled = true;
            } else {
                $course->enrolled = false;
            }
        }

        echo view('course.course_results')->with('courses', $courses)->render();
    }

    public function addcourse(Request $request)
    {
        $coursename = $request->course_name;
        $section = $request->section;
        $starttime = $request->start_time;
        $endtime = $request->end_time;
        $monday = $request->Monday;
        $tuesday = $request->Tuesday;
        $wednesday = $request->Wednesday;
        $thursday = $request->Thursday;
        $friday = $request->Friday;
        $days = [];
        if ($monday) {
            $days[] = "Monday";
        }
        if ($tuesday) {
            $days[] = "Tuesday";
        }
        if ($wednesday) {
            $days[] = "Wednesday";
        }
        if ($thursday) {
            $days[] = "Thursday";
        }
        if ($friday) {
            $days[] = "Friday";
        }
        $dayStr = implode(',', $days);

        DB::table('courses')->insert(array(
            'name'=>$coursename,
            'days' => $dayStr,
            'section'=>$section,
            'timing'=>date('h:ia', strtotime($starttime)).'-'.date('h:ia', strtotime($endtime)),
            'instructorid'=>Auth::id()));

        return Redirect::back()->with('message', 'Course Added Successfully!');;
    }
}