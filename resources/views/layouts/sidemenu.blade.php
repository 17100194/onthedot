@extends('layouts.app')

@section('content')
<div class="container" >
    <div class="row">
        <div class="col-md-3" style="padding-right: 0px; padding-left: 0px;">
            <div class="panel panel-default dashboard-options">
                <a href="<?php echo url('/home') ?>" style="display:block;" class="panel-heading active">Dashboard</a>
                <a href="<?php echo url('/meetings/requests') ?>" style="display:block;" class="panel-heading">Meeting Requests (<?=count($requests)?>)</a>
                <a href="<?php echo url('/course/all') ?>" style="display:block;" class="panel-heading">View My Courses</a>
                <a href="<?php echo url('/meetings') ?>" style="display:block;" class="panel-heading">View My Meetings</a>
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