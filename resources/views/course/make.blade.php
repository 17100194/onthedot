@extends('layouts.sidemenu')

@section('main')
    <div data-animation="fadeInUp">
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <i class="fa fa-check-circle"></i> <?php echo session()->pull('message') ?>
            </div>
        @endif
        <div class="heading heading-center m-b-40">
            <h2>Add a Course</h2>
            <div class="separator">
                <span>Please fill in the form below to add a course which you're teaching this semester</span>
            </div>
        </div>
            <div class="col-md-12">
                <form class="form-transparent-grey col-md-6 center" role="form" method="POST" action="{{ url('/addcourse') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('course_code') ? ' has-error has-feedback' : '' }}">
                        <label for="course_code">Course Code</label>
                        <input id="course_code" type="text" class="form-control" placeholder="e.g CS 100" name="course_code" required autofocus>
                        @if ($errors->has('course_code'))
                            <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                            <span class="help-block">
                                <strong>{{ $errors->first('course_code') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('course_name') ? ' has-error has-feedback' : '' }}">
                        <label for="course_name">Course Name</label>
                        <input id="course_name" type="text" class="form-control" name="course_name" required autofocus>
                        @if ($errors->has('course_name'))
                            <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                            <span class="help-block">
                                <strong>{{ $errors->first('course_name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('section') ? ' has-error has-feedback' : '' }}">
                        <label for="section">Section</label>
                        <input id="section" placeholder="Numeric value, 1 if only section..." type="text" class="form-control" name="section" required>
                        @if ($errors->has('section'))
                            <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                            <span class="help-block">
                                <strong>{{ $errors->first('section') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('start_time') ? ' has-error has-feedback' : '' }}">
                            <label for="start_time">Start Time</label>
                            <input name="start_time" id="start_time" class="form-control" type="time" required>
                            @if ($errors->has('start_time'))
                                <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                <span class="help-block">
                                    <strong>{{ $errors->first('start_time') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('end_time') ? ' has-error has-feedback' : '' }}">
                            <label for="end_time">End Time</label>
                            <input name="end_time" id="end_time" class="form-control" type="time" required>
                            @if ($errors->has('end_time'))
                                <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                <span class="help-block">
                                    <strong>{{ $errors->first('end_time') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <label>Days</label>
                    <br>
                    <label class="checkbox-inline"><input name="days[]" type="checkbox" value="Monday">Monday</label>
                    <label class="checkbox-inline"><input name="days[]" type="checkbox" value="Tuesday">Tuesday</label>
                    <label class="checkbox-inline"><input name="days[]" type="checkbox" value="Wednesday">Wednesday</label>
                    <label class="checkbox-inline"><input name="days[]" type="checkbox" value="Thursday">Thursday</label>
                    <label class="checkbox-inline"><input name="days[]" type="checkbox" value="Friday">Friday</label>
                    @if ($errors->has('days'))
                        <span class="help-block">
                        <strong>{{ $errors->first('days') }}</strong>
                    </span>
                    @endif
                    <div class="space"></div>
                    <div class="form-group{{ $errors->has('venue') ? ' has-error has-feedback' : '' }}">
                        <label for="venue">Venue</label>
                        <input id="venue" name="venue" class="form-control" type="text" required>
                        @if ($errors->has('venue'))
                            <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                            <span class="help-block">
                                <strong>{{ $errors->first('venue') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-shadow">Add Course</button>
                    </div>
                </form>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#start_time").focusout(function() {
                        var starttime = $("#start_time").val();
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
            </script>
    </div>
@endsection
