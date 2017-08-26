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
use Validator;

class CourseController extends Controller
{
    public function all(){
        $courses = DB::table('user_has_course')
            ->join('courses', 'user_has_course.courseid', '=', 'courses.courseid')
            ->where('user_has_course.userid', '=', Auth::id())->paginate(10);
        $active = 'courses';
        return view('course.all', compact('courses', 'active'));
    }

    public function courseDetails(Request $request){
        $course = DB::table('courses')
            ->join('users', 'users.id', '=', 'courses.instructorid')
            ->where('courses.courseid', '=', $request->courseid)
            ->select('courses.venue as venue','courses.courseid as courseid','courses.instructorid as instructorid','courses.coursecode as coursecode', 'courses.name as name', 'courses.section as section', 'courses.timing as timing', 'courses.days as days', 'users.name as instructor')
            ->first();
        return view('course.course_details',['course'=>$course])->render();
    }

    public function makeform()
    {
        $active = 'addcourse';
        if (Auth::user()->type == 'student'){
            return redirect()->to('course/enroll');
        }
        return view('course.make', compact('active'));
    }

    public function enroll(){
        $active = 'addcourse';
        if (Auth::user()->type == 'teacher'){
            return redirect()->to('course/make');
        }
        return view('course.enroll', compact('active'));
    }

    public function dropCourse(Request $request){
        $courseId = $request->courseid;
        $user = $this->getUserById(Auth::id());
        $course = $this->getCourseById($courseId);
        if ($user->id == $course->instructorid) {
            DB::table('user_has_course')->where('courseid', '=', intval($courseId))->delete();
            DB::table('courses')->where('courseid', '=', intval($courseId))->delete();
            session(['message' => 'Course Deleted Successfully']);
        } else {
            return url('dashboard');
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
        $validation = Validator::make($request->all(),[
           'searchterm'=>'required'
        ]);
        if ($validation->fails()){
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }
        $name = $request->searchterm;
        $courses = DB::table('courses AS c')
            ->join('users as u', 'u.id', '=', 'c.instructorid')
            ->where('c.name', 'LIKE', '%'.$name.'%')
            ->orwhere('c.coursecode', 'LIKE', '%'.$name.'%')
            ->select('u.name AS username', 'u.campusid', 'u.type', 'c.*')->paginate(10);

        foreach ($courses as $course) {
            if ($this->userHasCourse(Auth::id(), $course->courseid)) {
                $course->enrolled = true;
            } else {
                $course->enrolled = false;
            }
        }
        if ($request->ajax()){
            return view('course.course_results', ['courses' => $courses])->render();
        }
    }

    public function course_exists($code){
        $course = DB::table('courses')
            ->where('coursecode', '=', $code)->get();

        if (count($course) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addcourse(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'course_code'=>'required|regex:/^\w*\s\d+$/',
            'course_name'=>'required',
            'section'=>'required',
            'start_time'=>'required|after:09:00AM|before:06:00PM',
            'end_time'=>'required|after:09:00AM|before:06:00PM',
            'days'=>'required|min:1',
            'venue'=>'required'
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $coursename = $request->course_name;
        $section = $request->section;
        $coursecode = $request->course_code;
        $starttime = $request->start_time;
        $endtime = $request->end_time;
        $days = $request->days;
        $venue = $request->venue;
        $dayStr = implode(',', $days);

        if($this->course_exists($coursecode) == true){
            return Redirect::back()->withInput()->withErrors(array('course_code'=>'A course already exists with this course code'));
        }

        DB::table('courses')->insert(array(
            'name'=>$coursename,
            'days' => $dayStr,
            'section'=>$section,
            'coursecode'=>$coursecode,
            'timing'=>date('H:i', strtotime($starttime)).'-'.date('H:i', strtotime($endtime)),
            'venue'=>$venue,
            'instructorid'=>Auth::id()));
        $courseId = DB::table('courses')->where('coursecode', '=', $coursecode)->get()[0]->courseid;

        DB::table('user_has_course')->insert(array(
            'userid'=> Auth::id(),
            'courseid' => $courseId));

        return Redirect::back()->with('message', 'Course added successfully!');;
    }

    public function updateCourse(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code'=>'required|regex:/^\w*\s\d+$/',
            'name'=>'required',
            'section'=>'required',
            'time'=>'required|date_format:h:iA-h:iA',
            'days'=>'required',
            'venue'=>'required'
        ]);
        if ($validation->fails()){
            return response()->json(['success' => false, 'errors' => $validation->getMessageBag()->toArray()]);
        }
        $coursename = $request->name;
        $section = $request->section;
        $coursecode = $request->code;
        $time = $request->time;
        $days = $request->days;
        $venue = $request->venue;
        $courseid = $request->courseid;

        DB::table('courses')->where('courseid', '=', $courseid)->update(array(
            'name'=>$coursename,
            'days' => $days,
            'section'=>$section,
            'coursecode'=>$coursecode,
            'venue'=>$venue,
            'timing'=>date('H:i', strtotime(explode('-',$time)[0])).'-'.date('H:i', strtotime(explode('-',$time)[1]))));

        return response()->json(array('success' => 'Changes saved successfully!'));
    }

}