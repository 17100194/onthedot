@extends('layouts.app')

@section('content')
<div class="container" >
    <input id="activeTab" type="hidden">
    <div class="row">
        <div class="col-md-3" style="padding-right: 0px; padding-left: 0px;">
            <div class="panel panel-default dashboard-options">
                <ul style="list-style: none; padding-left: 0px;">
                    <li>
                        <a href="<?php echo url('/home') ?>" style="display:block;" class="active panel-heading">Dashboard</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" style="display:block;" class="panel-heading">Scheduled</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" style="display:block;" class="panel-heading">Meeting Requests <?php if(count($requests) > 0): ?>(<?=count($requests)?>)<?php endif; ?></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" style="display:block;" class="panel-heading">View My Courses</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" style="display:block;" class="panel-heading">View My Meetings</a>
                    </li>
                    <li>
                        <a href="<?php echo url('/group/make') ?>" style="display:block;" class="panel-heading">Make a Group</a>
                    </li>
                    <?php if($user->type == 'student'): ?>
                    <li>
                        <a href="<?php echo url('/course/enroll') ?>" style="display:block;" class="panel-heading">Enroll in a Course</a>
                    </li>
                    <?php elseif ($user->type == 'teacher'): ?>
                    <li>
                        <a href="<?php echo url('/course/make') ?>" style="display:block;" class="panel-heading">Add a Course</a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="javascript:void(0)" style="display:block;" class="panel-heading">View My Timetable</a>
                    </li>
                </ul>
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