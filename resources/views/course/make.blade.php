@extends('layouts.sidemenu')

@section('main')
    <h1>Add A Course</h1>
    <hr>
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/addcourse') }}">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="course_name" class="col-md-4 control-label">Course Name</label>

            <div class="col-md-6">
                <input id="course_name" type="text" class="form-control" name="course_name" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label for="section" class="col-md-4 control-label">Section</label>

            <div class="col-md-6">
                <input id="section" type="text" class="form-control" name="section" required>
            </div>
        </div>

        <div class="form-group">
            <label for="timing" class="col-md-4 control-label">Timing</label>

            <div class="col-md-6">
                <div class="col-md-3">
                    <label for="start_time">Start Time</label> <input name="start_time" type="time">
                </div>
                <div class="col-md-3">
                    <label for="end_time">End Time</label> <input name="end_time" type="time">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="days" class="col-md-4 control-label">Days</label>

            <div class="col-md-6">
                <input name="Monday" type="checkbox"> <label for="Monday">Monday</label>
                </br>
                <input name="Tuesday" type="checkbox"> <label for="Tuesday">Tuesday</label>
                </br>
                <input name="Wednesday" type="checkbox"> <label for="Wednesday">Wednesday</label>
                </br>
                <input name="Thursday" type="checkbox"> <label for="Thursday">Thursday</label>
                </br>
                <input name="Friday" type="checkbox"> <label for="Friday">Friday</label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Add a Course
                </button>
            </div>
        </div>
    </form>
@endsection
