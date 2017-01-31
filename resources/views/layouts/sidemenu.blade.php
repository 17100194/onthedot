@extends('layouts.app')

@section('content')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<div class="container" >
    <div class="row">
        <div class="col-md-3" style="padding-right: 0px;">
            <div class="panel panel-default dashboard-options">
                <a href="javascript:void(0)" style="display:block;" class="active panel-heading" >Dashboard</a>
                <a href="javascript:void(0)" style="display:block;" class="panel-heading">Scheduled</a>
                <a href="javascript:void(0)" style="display:block;" class="panel-heading">Meeting Requests <?php if(count($requests) > 0): ?>(<?=count($requests)?>)<?php endif; ?></a>
                <a href="javascript:void(0)" style="display:block;" class="panel-heading">View My Courses</a>
                <a href="javascript:void(0)" style="display:block;" class="panel-heading">View My Meetings</a>
                <a href="<?php echo url('/group/make') ?>" style="display:block;" class="panel-heading">Make a Group</a>
                <?php if($user->type == 'student'): ?>
                <a href="<?php echo url('/course/enroll') ?>" style="display:block;" class="panel-heading">Enroll in a Course</a>
                <?php elseif ($user->type == 'teacher'): ?>
                <a href="<?php echo url('/course/make') ?>" style="display:block;" class="panel-heading">Add a Course</a>
                <?php endif; ?>
                <a href="javascript:void(0)" style="display:block;" class="panel-heading">View My Timetable</a>
            </div>
        </div>
        <div class="col-md-9" style="padding-left: 0px;">
            <div class="panel panel-default">
                <div class="panel-body">
                    @yield('main')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection