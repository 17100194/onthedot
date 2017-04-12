@extends('layouts.sidemenu')

@section('main')
    <h4 style="text-align: center;"><a>My Timetable</a></h4>
    <hr>
    <div class="courses">
        <?php foreach($allCourses as $course): ?>
            <?php
        $left = 0;
        ?>
        <?php if(!$course): ?>
            <?php continue; ?>
        <?php endif; ?>
            <?php foreach($course->days as $day): ?>
            <?php
            $left = 144;
            switch ($day) {
                case "Monday":
                    break;
                case "Tuesday":
                    $left += 167;
                    break;
                case "Wednesday":
                    $left += 167*2;
                    break;
                case "Thursday":
                    $left += 167*3;
                    break;
                case "Friday":
                    $left += 167*4;
                    break;
            }
            ?>
            @if($course->type == 'meeting')
                <div class="ttElement <?php if($course->type == 'meeting'): ?>meetingElement<?php endif; ?>" data-toggle="modal" data-target="#<?=$course->meetingid?>" style="<?php if($course->color != ""): ?>background: <?= $course->color ?>;<?php endif; ?>padding: 5px; text-align: center; height: <?=$course->height?>px; top: <?= 78+$course->startingHeight?>px; left:<?=$left?>px;">
                    <label style="color: white;">Meeting</label>
                </div>
                <div id="<?=$course->meetingid?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 style="text-align: center;">Meeting Details</h3>
                            </div>
                            <div class="modal-body">
                                <h5>Meeting with: <strong><?=$course->with?></strong></h5>
                                <br>
                                <h5>Date: <strong><?=$course->name?> - <?=$course->timing?></strong></h5>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            <div class="ttElement" style="<?php if($course->color != ""): ?>background: <?= $course->color ?>;<?php endif; ?>padding: 5px; text-align: center; height: <?=$course->height?>px; top: <?= 78+$course->startingHeight?>px; left:<?=$left?>px;">
                <label style="color: white;"><?=$course->name?></label>
                <br>
                <label style="color: white;"><?=$course->timing?></label>
            </div>
            @endif
            <?php endforeach; ?>

        <?php endforeach; ?>
    </div>
    <div class="myTimetable">
        <table id="timetable" class="table timetable-full table-bordered timetable">
            <thead>
            <tr>
                <th></th>
                <th style="width: 167px;">Monday <?= date("d/m/Y",strtotime('monday')); ?></th>
                <th style="width: 167px;">Tuesday <?= date("d/m/Y",strtotime('tuesday')); ?></th>
                <th style="width: 167px;">Wednesday <?= date("d/m/Y",strtotime('wednesday')); ?></th>
                <th style="width: 167px;">Thursday <?= date("d/m/Y",strtotime('thursday')); ?></th>
                <th style="width: 167px;">Friday <?= date("d/m/Y",strtotime('friday')); ?></th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">8:00AM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">9:00AM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">10:00AM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">11:00AM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">12:00PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">1:00PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">2:00PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">3:00PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">4:00PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">5:00PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="addModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p>Add an Event</p>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="eventType">Type of Event</label>
                        <select class="form-control" id="eventType">
                            <option>Meeting</option>
                            <option>Course</option>
                        </select>
                        <div class="meetingForm">
                            <h4>Enter Meeting Details</h4>
                            <form role="form" class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <div class="col-md-12">
                                            <label for="start_time">Start Time</label>
                                            <div class="input-group">
                                                <span id="hr" class="input-group-addon hr"></span>
                                                <input type="number" min="0" max="59" id="minute" class="form-control minute" aria-describedby="hr"><span class="input-group-addon ampm" id="ampm"></span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-md-12">
                                            <label for="end_time">Duration</label>
                                            <input id="duration" type="number" min="0" class="form-control" placeholder="Minutes">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="day" class="col-md-3 control-label">Date</label>
                                    <label id="day" class="day col-md-4 control-label"></label>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <select class="searchuser" multiple="multiple" style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group" style="text-align: center;">
                                    <a class="btn btn-primary addMeeting">Send Meeting Request</a>
                                </div>
                            </form>
                        </div>
                        <div class="courseForm" style="display:none;">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

        });
    </script>
@endsection