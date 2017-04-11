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

class CourseController extends Controller
{
    public function all(){
        $courses = DB::table('user_has_course')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('user_has_course.userid', '=', Auth::id())->get();
        foreach ($courses as $course){
            $course->instructor = $this->getUserById($course->instructorid);
        }
        $active = 'courses';
        return view('course.all', compact('courses', 'active'));
    }

    public function makeform()
    {
        $active = 'addcourse';
        return view('course.make', compact('active'));
    }

    public function enroll(){
        $active = 'addcourse';
        return view('course.enroll', compact('active'));
    }

    public function dropCourse(Request $request){
        $courseId = $request->courseid;

        $user = $this->getUserById(Auth::id());
        if ($user->type == "teacher") {
            // delete course
            DB::table('user_has_course')->where('courseid', '=', intval($courseId))->delete();
            DB::table('courses')->where('courseid', '=', intval($courseId))->delete();
            session(['message' => 'Course Deleted Successfully']);
        } else {
            DB::table('user_has_course')->where('userid', '=', Auth::id())->where('courseid', '=', intval($courseId))->delete();
            session(['message' => 'Course Dropped Successfully']);
        }


        return 'success';
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
            ->orwhere('c.coursecode', 'LIKE', '%'.$name.'%')
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

    public function course_exists($name, $section){
        $course = DB::table('courses')
            ->where('name', '=', $name)
            ->where('section', '=', $section)->get();

        if (count($course) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addcourse(Request $request)
    {
        $coursename = $request->course_name;
        $section = $request->section;
        $coursecode = $request->course_code;
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

        if($this->course_exists($coursename, $section) == true){
            return Redirect::back()->with('error', 'Course Already Exists');;
        }

        DB::table('courses')->insert(array(
            'name'=>$coursename,
            'days' => $dayStr,
            'section'=>$section,
            'coursecode'=>$coursecode,
            'timing'=>date('H:i', strtotime($starttime)).'-'.date('H:i', strtotime($endtime)),
            'instructorid'=>Auth::id()));
        $courseId = DB::table('courses')->where('coursecode', '=', $coursecode)->get()[0]->courseid;

        DB::table('user_has_course')->insert(array(
            'userid'=> Auth::id(),
            'courseid' => $courseId));

        return Redirect::back()->with('message', 'Course Added Successfully!');;
    }

    public function editCourse(Request $request, $id) {
        $course = $this->getCourseById($id);
//        $active = 'addcourse';
        return view('course.edit', compact('course'));
    }

    public function updateCourse(Request $request)
    {
        $coursename = $request->course_name;
        $section = $request->section;
        $coursecode = $request->course_code;
        $starttime = $request->start_time;
        $endtime = $request->end_time;
        $monday = $request->Monday;
        $tuesday = $request->Tuesday;
        $wednesday = $request->Wednesday;
        $thursday = $request->Thursday;
        $friday = $request->Friday;
        $courseId = $request->courseId;

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


        DB::table('courses')->where('courseid', '=', $courseId)->update(array(
            'name'=>$coursename,
            'days' => $dayStr,
            'section'=>$section,
            'coursecode'=>$coursecode,
            'timing'=>date('H:i', strtotime($starttime)).'-'.date('H:i', strtotime($endtime))));


        return Redirect::back()->with('message', 'Course Modified Successfully!');;
    }

}