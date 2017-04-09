@extends('layouts.app')

@section('content')
    <div class="container" style="width: 90%; margin-top: 20px;">
        <div class="row">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#students" aria-controls="profile" role="tab" data-toggle="tab">Students <span class="badge"><?= count($users)?></span></a></li>
                <li role="presentation"><a href="#instructors" aria-controls="messages" role="tab" data-toggle="tab">Instructors <span class="badge"><?= count($instructors)?></span></a></li>
                <li role="presentation"><a href="#groups" aria-controls="settings" role="tab" data-toggle="tab">Groups <span class="badge"><?= count($groups)?></span></a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="students">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" style="border-bottom: 3px solid grey;">
                                    <div class="col-xs-3" style="margin-left: 10px;">
                                        <h3>Name</h3>
                                    </div>
                                    <div class="col-xs-3">
                                        <h3>Type</h3>
                                    </div>
                                    <div class="col-xs-3">
                                        <h3>ID</h3>
                                    </div>
                                    <div class="col-xs-3">
                                    </div>
                                </div>
                                @if (count($users) === 0)
                                    No users found with the query
                                @elseif (count($users) >= 1)
                                    <ul style="list-style: none;">
                                        @foreach($users as $user)
                                            @if($user->id != Auth::id())
                                                <li>
                                                    <div style="padding: 15px;">
                                                        <div class="row">
                                                            <div class="col-xs-3">
                                                                <h4><?= $user->name ?></h4>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <h4><?= $user->type ?></h4>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <h4><?= $user->campusid ?></h4>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <button type="button" class="btn btn-primary schedule" data-toggle="modal" data-target="#UserTimetable_user<?= $user->id ?>">Schedule Meeting</button>
                                                            </div>
                                                        </div>
                                                        <div class="modal fade bs-example-modal-lg" id="UserTimetable_user<?= $user->id ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <p>Select a Time slot</p>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <input id="userid" type="hidden" value="<?= $user->id ?>">
                                                                        <div class="courses">
                                                                            <?php foreach($hashMap[$user->id] as $course): ?>
                                                                            <?php
                                                                            $left = 0;
                                                                            ?>
                                                                            <?php if(!$course): ?>
                                                                                <?php continue; ?>
                                                                                <?php else: ?>
                                                                            <?php foreach($course->days as $day): ?>
                                                                            <?php
                                                                            $left = 90;
                                                                            switch ($day) {
                                                                                case "Monday":
//
                                                                                    break;
                                                                                case "Tuesday":
                                                                                    $left += 155;
                                                                                    break;
                                                                                case "Wednesday":
                                                                                    $left += 155*2;
                                                                                    break;
                                                                                case "Thursday":
                                                                                    $left += 155*3;
                                                                                    break;
                                                                                case "Friday":
                                                                                    $left += 155*4;
                                                                                    break;
                                                                            }
                                                                            ?>
                                                                            <div class="ttElement" style="<?php if($course->loggedIn): ?>z-index: 3000; opacity: 0.5;<?php else: ?>z-index: 2000; <?php endif; ?>background-color: <?=$course->color?>;padding: 5px; width: 155px; text-align: center; height: <?=$course->height?>px; top: <?= 78+$course->startingHeight?>px; left:<?=$left?>px;">
                                                                                <label style="color: white;"><?=$course->name?></label>

                                                                            </div>
                                                                            <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                        <table id="timetable" class="table table-bordered timetable">
                                                                            <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th style="width: 156px;">Monday <?= date("d/m/Y",strtotime('monday')); ?></th>
                                                                                <th style="width: 156px;">Tuesday <?= date("d/m/Y",strtotime('tuesday')); ?></th>
                                                                                <th style="width: 156px;">Wednesday <?= date("d/m/Y",strtotime('wednesday')); ?></th>
                                                                                <th style="width: 156px;">Thursday <?= date("d/m/Y",strtotime('thursday')); ?></th>
                                                                                <th style="width: 156px;">Friday <?= date("d/m/Y",strtotime('friday')); ?></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr>
                                                                                <th>8:00am</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>9:00AM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>10:00AM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>11:00AM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>12:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>1:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>2:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>3:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>4:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>5:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <hr>
                                                                        <div class="slot_details" style="display:none;">
                                                                            <h4>Enter Meeting Details</h4>
                                                                            <form role="form" class="form-horizontal">
                                                                                <div class="form-group">
                                                                                    <label for="timing" class="col-md-4 control-label">Timing</label>
                                                                                    <div class="col-md-8">
                                                                                        <div class="col-md-4">
                                                                                            <label for="start_time">Start Time</label>
                                                                                            <div class="input-group">
                                                                                                <span id="hr" class="input-group-addon hr"></span>
                                                                                                <input type="number" min="0" max="59" id="minute" class="form-control minute" aria-describedby="hr"><span class="input-group-addon ampm" id="ampm"></span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <label for="end_time">Duration</label>
                                                                                            <input id="duration" type="number" min="0" class="form-control duration" placeholder="Minutes">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="day" class="col-md-4 control-label">Date</label>
                                                                                    <label id="day" class="day col-md-3 control-label"></label>
                                                                                </div>
                                                                                <hr>
                                                                                <div class="form-group" style="text-align: center;">
                                                                                    <a class="btn btn-primary select_user">Send Meeting Request</a>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <div class="alert alert-success" style="display:none;">
                                                                            <strong>Meeting Request Send!</strong> It will appear as soon as the participant agrees.
                                                                        </div>
                                                                        <div class="alert alert-warning" style="display:none;">
                                                                            <strong>Conflict Detected!</strong> Meeting cannot be requested at the given time.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="instructors">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" style="border-bottom: 3px solid grey;">
                                    <div class="col-xs-3" style="margin-left: 10px;">
                                        <h3>Name</h3>
                                    </div>
                                    <div class="col-xs-3">
                                        <h3>Type</h3>
                                    </div>
                                    <div class="col-xs-3">
                                        <h3>ID</h3>
                                    </div>
                                    <div class="col-xs-3">
                                    </div>
                                </div>
                                @if (count($instructors) === 0)
                                    No users found with the query
                                @elseif (count($users) >= 1)
                                    <ul style="list-style: none;">
                                        @foreach($instructors as $instructor)
                                            @if($instructor->id != Auth::id())
                                                <li>
                                                    <div style="padding: 15px;">
                                                        <div class="row">
                                                            <div class="col-xs-3">
                                                                <h4><?= $instructor->name ?></h4>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <h4><?= $instructor->type ?></h4>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <h4><?= $instructor->campusid ?></h4>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <button type="button" class="btn btn-primary schedule" data-toggle="modal" data-target="#instructorTimetable_user<?= $instructor->id ?>">Schedule Meeting</button>
                                                            </div>
                                                        </div>
                                                        <div class="modal fade bs-example-modal-lg" id="instructorTimetable_user<?= $instructor->id ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <p>Select a Time slot</p>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <input id="userid" type="hidden" value="<?= $instructor->id ?>">
                                                                        <div class="courses">
                                                                            <?php foreach($hashMap[$instructor->id] as $course): ?>
                                                                            <?php
                                                                            $left = 0;
                                                                            ?>
                                                                            <?php if(!$course): ?>
                                                                                    <?php continue; ?>
                                                                                    <?php else: ?>
                                                                            <?php foreach($course->days as $day): ?>
                                                                            <?php
                                                                            $left = 90;
                                                                            switch ($day) {
                                                                                case "Monday":
                                                                                    //
                                                                                    break;
                                                                                case "Tuesday":
                                                                                    $left += 155;
                                                                                    break;
                                                                                case "Wednesday":
                                                                                    $left += 155*2;
                                                                                    break;
                                                                                case "Thursday":
                                                                                    $left += 155*3;
                                                                                    break;
                                                                                case "Friday":
                                                                                    $left += 155*4;
                                                                                    break;
                                                                            }
                                                                            ?>
                                                                            <div class="ttElement" style="<?php if($course->loggedIn): ?>z-index: 3000; opacity: 0.5;<?php else: ?>z-index: 2000; <?php endif; ?>background-color: <?=$course->color?>;padding: 5px; width: 155px; text-align: center; height: <?=$course->height?>px; top: <?= 78+$course->startingHeight?>px; left:<?=$left?>px;">
                                                                                <label style="color: white;"><?=$course->name?></label>

                                                                            </div>
                                                                            <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                        <table id="timetable" class="table table-bordered timetable">
                                                                            <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th style="width: 156px;">Monday <?= date("d/m/Y",strtotime('monday')); ?></th>
                                                                                <th style="width: 156px;">Tuesday <?= date("d/m/Y",strtotime('tuesday')); ?></th>
                                                                                <th style="width: 156px;">Wednesday <?= date("d/m/Y",strtotime('wednesday')); ?></th>
                                                                                <th style="width: 156px;">Thursday <?= date("d/m/Y",strtotime('thursday')); ?></th>
                                                                                <th style="width: 156px;">Friday <?= date("d/m/Y",strtotime('friday')); ?></th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr>
                                                                                <th>8:00am</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>9:00AM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>10:00AM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>11:00AM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>12:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>1:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>2:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>3:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>4:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>5:00PM</th>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <hr>
                                                                        <div class="slot_details" style="display:none;">
                                                                            <h4>Enter Meeting Details</h4>
                                                                            <form role="form" class="form-horizontal">
                                                                                <div class="form-group">
                                                                                    <label for="timing" class="col-md-4 control-label">Timing</label>
                                                                                    <div class="col-md-8">
                                                                                        <div class="col-md-4">
                                                                                            <label for="start_time">Start Time</label>
                                                                                            <div class="input-group">
                                                                                                <span id="hr" class="input-group-addon hr"></span>
                                                                                                <input type="number" min="0" max="59" id="minute" class="form-control minute" aria-describedby="hr"><span class="input-group-addon ampm" id="ampm"></span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <label for="end_time">Duration</label>
                                                                                            <input id="duration" type="number" min="0" class="form-control duration" placeholder="Minutes">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="day" class="col-md-4 control-label">Date</label>
                                                                                    <label id="day" class="day col-md-3 control-label"></label>
                                                                                </div>
                                                                                <hr>
                                                                                <div class="form-group" style="text-align: center;">
                                                                                    <a class="btn btn-primary select_user">Send Meeting Request</a>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <div class="alert alert-success" style="display:none;">
                                                                            <strong>Meeting Request Send!</strong> It will appear as soon as the participant agrees.
                                                                        </div>
                                                                        <div class="alert alert-warning" style="display:none;">
                                                                            <strong>Conflict Detected!</strong> Meeting cannot be requested at the given time.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="groups">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" style="border-bottom: 3px solid grey;">
                                    <div class="col-xs-3" style="margin-left: 10px;">
                                        <h3>Name</h3>
                                    </div>
                                    <div class="col-xs-3">
                                        <h3>Admin</h3>
                                    </div>
                                    <div class="col-xs-3">
                                        <h3>Members</h3>
                                    </div>
                                    <div class="col-xs-3">
                                    </div>
                                </div>
                                @if (count($groups) === 0)
                                    No groups with the query
                                @elseif (count($groups) >= 1)
                                    <ul style="list-style: none;">
                                        @foreach($groups as $index=>$group)
                                            <li>
                                                <div style="padding: 15px;">
                                                    <div class="row">
                                                        <div class="col-xs-3">
                                                            <h4><?= $group->name ?></h4>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <h4><?= $group->creator_name->name ?> (<?= $group->creator_name->campusid ?>)</h4>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            @foreach($group->userlist as $member)
                                                            <h4><?= $member->name ?> (<?= $member->campusid?>)</h4>
                                                                @endforeach
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <button type="button" class="btn btn-primary schedule" data-toggle="modal" data-target="#Group_<?= $group->id ?>">Schedule Meeting</button>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade bs-example-modal-lg" id="Group_<?= $group->id ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <p>Select a Time slot</p>
                                                                </div>
                                                                <div class="timetable-legend">
                                                                    <ul>
                                                                        <li>Your courses: <div class="yourCourses"></div></li>
                                                                        <li>Their courses: <div class="theirCourses"></div></li>
                                                                        <li>Meetings: <div class="meetingBox"></div></li>
                                                                    </ul>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="courses">
                                                                        <?php foreach($groupMap[$group->id] as $course): ?>
                                                                        <?php
                                                                        $left = 4;
                                                                        ?>
                                                                        <?php if(!$course): ?>
                                                                                <?php continue; ?>
                                                                                <?php else: ?>
                                                                        <?php foreach($course->days as $day): ?>
                                                                        <?php
                                                                        $left = 92;
                                                                        switch ($day) {
                                                                            case "Monday":
//
                                                                                break;
                                                                            case "Tuesday":
                                                                                $left += 155;
                                                                                break;
                                                                            case "Wednesday":
                                                                                $left += 155*2;
                                                                                break;
                                                                            case "Thursday":
                                                                                $left += 155*3;
                                                                                break;
                                                                            case "Friday":
                                                                                $left += 155*4;
                                                                                break;
                                                                        }
                                                                        ?>
                                                                        <div class="ttElement" style="<?php if($course->loggedIn): ?>z-index: 3000; opacity: 0.5;<?php else: ?>z-index: 2000; <?php endif; ?>background-color: <?=$course->color?>;padding: 5px; width: 155px; text-align: center; height: <?=$course->height?>px; top: <?= 78+$course->startingHeight?>px; left:<?=$left?>px;">
                                                                            <label style="color: white;"><?=$course->name?></label>

                                                                        </div>
                                                                        <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                    <input id="groupid" style="display: block;" type="hidden" value="<?= $group->id ?>">
                                                                    <table class="table table-bordered timetable">
                                                                        <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th style="width: 156px;">Monday <?= date("d/m/Y",strtotime('monday')); ?></th>
                                                                            <th style="width: 156px;">Tuesday <?= date("d/m/Y",strtotime('tuesday')); ?></th>
                                                                            <th style="width: 156px;">Wednesday <?= date("d/m/Y",strtotime('wednesday')); ?></th>
                                                                            <th style="width: 156px;">Thursday <?= date("d/m/Y",strtotime('thursday')); ?></th>
                                                                            <th style="width: 156px;">Friday <?= date("d/m/Y",strtotime('friday')); ?></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <th>8:00am</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>9:00AM</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>10:00AM</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>11:00AM</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>12:00PM</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>1:00PM</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>2:00PM</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>3:00PM</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>4:00PM</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>5:00PM</th>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div class="slot_details" style="display:none;">
                                                                        <h4>Enter Meeting Details</h4>
                                                                        <form role="form" class="form-horizontal">
                                                                            <div class="form-group">
                                                                                <label for="timing" class="col-md-4 control-label">Timing</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="col-md-4">
                                                                                        <label for="start_time">Start Time</label>
                                                                                        <div class="input-group">
                                                                                            <span id="hr" class="input-group-addon hr"></span>
                                                                                            <input type="number" min="0" max="59" id="minute" class="form-control minute" aria-describedby="hr"><span class="input-group-addon ampm" id="ampm"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label for="end_time">Duration</label>
                                                                                        <input id="duration" type="number" min="0" class="form-control duration" placeholder="Minutes">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="day" class="col-md-4 control-label">Date</label>
                                                                                <label id="day" class="day col-md-3 control-label"></label>
                                                                            </div>
                                                                            <hr>
                                                                            <div class="form-group" style="text-align: center;">
                                                                                <a id="select_group" class="btn btn-primary">Send Meeting Request</a>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="alert alert-success" style="display:none;">
                                                                        <strong>Meeting Request Send!</strong> It will appear as soon as the participant agrees.
                                                                    </div>
                                                                    <div class="alert alert-warning" style="display:none;">
                                                                        <strong>Conflict Detected!</strong> Meeting cannot be requested at the given time.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.search-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    </script>

    <script type="text/javascript">
        $('.minute').change(function () {
            if($(this).val().length === 1){
                $(this).val('0'+$(this).val());
            }
        });
        $('.select_user').on('click', function(){
            var start_time = $(this).parents('.modal-body').find('.hr').text()+$(this).parents('.modal-body').find('.minute').val()+$(this).parents('.modal-body').find('.ampm').text();
            var hrs = start_time.split(":")[0];
            var mins = start_time.split(":")[1].substr(1,2);
            var amPM = start_time.split(":")[1].substr(3,4);
            var duration = $(this).parents('.modal-body').find('.duration').val();
            var day = $(this).parents('.modal-body').find('.day').text().split(' ')[0];
            var date = $(this).parents('.modal-body').find('.day').text().split(' ')[1];
            var t = $(this);
            if(parseInt(mins) >= 60){
                alert('Invalid Start Time');
                return;
            }
            else{
                mins = (parseInt(mins) + parseInt(duration)).toString();
                var hrs_to_add = Math.floor(parseInt(mins) / 60);
                mins = (parseInt(mins) % 60).toString();
                var newhrs = (parseInt(hrs) + hrs_to_add).toString();
                if(parseInt(newhrs) >= 12 && parseInt(hrs) < 12){
                    if(amPM == 'AM'){
                        amPM = 'PM';
                    } else {
                        amPM = 'AM';
                    }
                }
                newhrs = (parseInt(newhrs) % 12).toString();
                if(newhrs == '0'){
                    newhrs = '12';
                }
                if (mins.length === 1) {
                    mins = '0'+mins;
                }
            }
            var end_time = newhrs +":"+mins+amPM;
            var time = start_time + '-' + end_time;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./schedule",
                data: {
                    Time: time,
                    Date: date,
                    Day: day,
                    User: $(this).parents('.modal-body').find('#userid').val()
                },
                success: function(data) {
                    if(data == 'error'){
                        t.parents('.modal-body').find('.alert-warning').show();
                        window.setTimeout(function () {
                            t.parents('.modal-body').find(".alert-warning").hide();
                            {{--$(".alert-warning").fadeTo(500, 0).slideUp(500, function () {--}}
                                {{--window.location.href = "{{URL::to('/home')}}";--}}
                            {{--});--}}
                        }, 3000);
                    } else {
                        t.parents('.modal-body').find('.alert-success').show();
                        window.setTimeout(function () {
                            t.parents('.modal-body').find(".alert-success").hide();
                            {{--$(".alert-warning").fadeTo(500, 0).slideUp(500, function () {--}}
                            {{--window.location.href = "{{URL::to('/home')}}";--}}
                            {{--});--}}
                        }, 3000);
                    }
                },
                error: function (xhr, status) {
                }
            });
        });
        $('#select_group').on('click', function(){
            var start_time = $(this).parents('.modal-body').find('.hr').text()+$(this).parents('.modal-body').find('.minute').val()+$(this).parents('.modal-body').find('.ampm').text();
            var hrs = start_time.split(":")[0];
            var mins = start_time.split(":")[1].substr(1,2);
            var amPM = start_time.split(":")[1].substr(3,4);
            var duration = $(this).parents('.modal-body').find('.duration').val();
            var day = $(this).parents('.modal-body').find('.day').text().split(' ')[0];
            var date = $(this).parents('.modal-body').find('.day').text().split(' ')[1];
            var t = $(this);
            if(parseInt(mins) >= 60){
                alert('Invalid Start Time');
                return;
            } else {
                mins = (parseInt(mins) + parseInt(duration)).toString();
                var hrs_to_add = Math.floor(parseInt(mins) / 60);
                mins = (parseInt(mins) % 60).toString();
                var newhrs = (parseInt(hrs) + hrs_to_add).toString();
                if(parseInt(newhrs) >= 12 && parseInt(hrs) < 12){
                    if(amPM == 'AM'){
                        amPM = 'PM';
                    } else {
                        amPM = 'AM';
                    }
                }
                newhrs = (parseInt(newhrs) % 12).toString();
                if(newhrs == '0'){
                    newhrs = '12';
                }
                if (mins.length === 1) {
                    mins = '0'+mins;
                }
            }
            var end_time = newhrs +":"+mins+amPM;
            var time = start_time + '-' + end_time;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./scheduleGroup",
                data: {
                    Time: time,
                    Date: date,
                    Day: day,
                    Group: $(this).parents('.modal-body').find('#groupid').val()
                },
                success: function(data) {
                    if(data == 'error'){
                        t.parents('.modal-body').find('.alert-warning').show();
                        window.setTimeout(function () {
                            t.parents('.modal-body').find(".alert-warning").hide();
                            {{--$(".alert-warning").fadeTo(500, 0).slideUp(500, function () {--}}
                            {{--window.location.href = "{{URL::to('/home')}}";--}}
                            {{--});--}}
                        }, 3000);

                    } else {
                        t.parents('.modal-body').find('.alert-success').show();
                        window.setTimeout(function () {
                            t.parents('.modal-body').find(".alert-success").hide();
                            {{--$(".alert-warning").fadeTo(500, 0).slideUp(500, function () {--}}
                            {{--window.location.href = "{{URL::to('/home')}}";--}}
                            {{--});--}}
                        }, 3000);
                    }
                },
                error: function (xhr, status) {
                }
            });
        });
        $('.timetable td:not(:first)').hover(function () {
            var t = $(this);
//            $(this).css('background-color', 'green')
            $(this).click(function () {
                $(this).parents('.modal-body').find('.slot_details').show();
                var time = $(this).closest('tr').children('th').text();
                $('.hr').html(time.split(':')[0]+" : ");
                $('.ampm').html(time.slice(-2));
                var cell = $(this).closest('td');
                var cellIndex = cell[0].cellIndex
                $('.day').html(t.parents('.modal-body').find('.timetable')[0].rows[0].cells[cellIndex].innerText);
                $(this).parents('.modal').animate({
                    scrollTop: $(this).parents('.modal-body').find('.slot_details').offset().top
                }, 1000);
            });
        }, function () {
            $(this).css('background-color', '')
        });
    </script>
@endsection
