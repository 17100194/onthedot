@extends('layouts.app')

@section('content')
    <?php
        if (!isset($active)) {
            $active = 'dashboard';
        }
    ?>
<div class="container" style="width:90%;">
    <div class="row">
        <div class="col-md-2" style="padding-right: 0px; padding-left: 0px;">
            <div class="panel panel-default dashboard-options">
                <a href="<?php echo url('/home') ?>" style="display:block;" class="panel-heading <?php if ($active == "dashboard"): ?>active<?php endif; ?>">Dashboard</a>
                <a href='javascript:void(0);' style="display:block;" class="panel-heading <?php if ($active == "meeting"): ?>active<?php endif; ?>">Requested Meetings</a>
                <a href="<?php echo url('/meetings/requests') ?>" style="display:block;" class="panel-heading <?php if ($active == "requests"): ?>active<?php endif; ?>">Meeting Requests (<?=count($requests)?>)</a>
                <a href="<?php echo url('/course/all') ?>" style="display:block;" class="panel-heading <?php if ($active == "courses"): ?>active<?php endif; ?>">View My Courses</a>
                <a href="<?php echo url('/meetings') ?>" style="display:block;" class="panel-heading <?php if ($active == "view-meeting"): ?>active<?php endif; ?>">View My Meetings</a>
                <a href="<?php echo url('/group/all') ?>" style="display:block;" class="panel-heading <?php if ($active == "mygroups"): ?>active<?php endif; ?>">View My Groups</a>
                <a href="<?php echo url('/group/make') ?>" style="display:block;" class="panel-heading <?php if ($active == "group"): ?>active<?php endif; ?>">Make a Group</a>
                <?php if($user->type == 'student'): ?>
                <a href="<?php echo url('/course/enroll') ?>" style="display:block;" class="panel-heading <?php if ($active == "addcourse"): ?>active<?php endif; ?>">Enroll in a Course</a>
                <?php elseif ($user->type == 'teacher'): ?>
                <a href="<?php echo url('/course/make') ?>" style="display:block;" class="panel-heading <?php if ($active == "addcourse"): ?>active<?php endif; ?>">Add a Course</a>
                <?php endif; ?>
                <a href="<?php echo url('/timetable') ?>" style="display:block;" class="panel-heading <?php if ($active == "timetable"): ?>active<?php endif; ?>">View My Timetable</a>
            </div>
        </div>
        <div class="col-md-10" style="padding-left: 0px; padding-right: 0px;">
            <div class="panel panel-default">
                <div class="panel-body">
                    @yield('main')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection