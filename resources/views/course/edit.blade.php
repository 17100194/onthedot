@extends('layouts.sidemenu')

@section('main')
    <h1>Edit Course</h1>
    <hr>
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->pull('message') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-warning">
            {{ session()->pull('error') }}
        </div>
    @endif
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/course/update') }}">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="course_code" class="col-md-4 control-label">Course Code</label>

            <div class="col-md-6">
                <input id="course_code" type="text" class="form-control" name="course_code" value="<?=$course->coursecode?>" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label for="course_name" class="col-md-4 control-label">Course Name</label>

            <div class="col-md-6">
                <input id="course_name" type="text" class="form-control" value="<?=$course->name?>" name="course_name" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label for="section" class="col-md-4 control-label">Section</label>

            <div class="col-md-6">
                <input id="section" placeholder="Numeric value, 1 if only section..." value="<?=$course->section?>" type="text" class="form-control" name="section" required>
            </div>
        </div>
        <input type="hidden" name="courseId" value="<?=$course->courseid?>" />
        <div class="form-group">
            <label for="timing" class="col-md-4 control-label">Timing</label>
            <?php
            $startTime = explode('-', $course->timing)[0];
            $startTime = date('h:i', strtotime($startTime));

            $endTime = explode('-', $course->timing)[1];
            $endTime = date('h:i', strtotime($endTime));
            ?>
            <div class="col-md-6">
                <div class="col-md-3">
                    <label for="start_time">Start Time</label> <input name="start_time" value="<?=$startTime?>" id="start_time" type="time" required>
                </div>
                <div class="col-md-3">
                    <label for="end_time">End Time</label> <input name="end_time" value="<?=$endTime?>" id="end_time" type="time" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="days" class="col-md-4 control-label">Days</label>
            <?php

                $daysArray = explode(',', $course->days);
                $mondayChecked = false;
                $tuesdayChecked = false;
                $wednesdayChecked = false;
                $thursdayChecked = false;
                $fridayChecked = false;

                foreach ($daysArray as $day) {
                    if ($day == "Monday") {
                        $mondayChecked = true;
                    }

                    if ($day == "Tuesday") {
                        $tuesdayChecked = true;
                    }

                    if ($day == "Wednesday") {
                        $wednesdayChecked = true;
                    }

                    if ($day == "Thursday") {
                        $thursdayChecked = true;
                    }

                    if ($day == "Friday") {
                        $fridayChecked = true;
                    }
                }

            ?>
            <div class="col-md-6">
                <input name="Monday" <?php if($mondayChecked):?>checked="checked"<?php endif; ?> type="checkbox"> <label for="Monday">Monday</label>
                </br>
                <input name="Tuesday" <?php if($tuesdayChecked):?>checked="checked"<?php endif; ?> type="checkbox"> <label for="Tuesday">Tuesday</label>
                </br>
                <input name="Wednesday" <?php if($wednesdayChecked):?>checked="checked"<?php endif; ?> type="checkbox"> <label for="Wednesday">Wednesday</label>
                </br>
                <input name="Thursday" <?php if($thursdayChecked):?>checked="checked"<?php endif; ?> type="checkbox"> <label for="Thursday">Thursday</label>
                </br>
                <input name="Friday" <?php if($fridayChecked):?>checked="checked"<?php endif; ?> type="checkbox"> <label for="Friday">Friday</label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() {

            $("#start_time").focusout(function() {
                var starttime = $("#start_time").val();
//                console.log(starttime);

                if ( starttime === "")  {
                    return false;
                } else {
                    var hrs = starttime.split(":")[0];
                    var mins = starttime.split(":")[1];

                    var newhr = hrs;
                    var newmin = mins;


                    if (parseInt(mins) >= 20) {
                        newmin = (parseInt(mins) - 20).toString();
                        newhr = (parseInt(hrs) + 1).toString();
                        if (hrs ===  "23") {
                            newhr = "00";

                        }
                        if (newhr.length === 1) {
                            newhr = '0'+newhr;
                        }
                        if (newmin.length === 1) {
                            newmin = '0'+newmin;
                        }
                    } else {
                        newmin = (parseInt(mins) + 40).toString();
                    }
                    var newTime = newhr +":"+newmin;
                    $("#end_time").val(newTime);
                }

            })

        });
        if($('.alert')) {
            $('.alert').show();
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 3000);
        }
    </script>
@endsection
