@extends('layouts.sidemenu')

@section('main')
    <h4>My Timetable</h4>
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
            $left = 92;
            switch ($day) {
                case "Monday":
//
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
            <div class="ttElement" style="<?php if($course->color != ""): ?>background: <?= $course->color ?>;<?php endif; ?>padding: 5px; text-align: center; height: <?=$course->height?>px; top: <?= 78+$course->startingHeight?>px; left:<?=$left?>px;">
                <label style="color: white;"><?=$course->name?></label>
                <br>
                <label style="color: white;"><?=$course->timing?></label>
            </div>
            <?php endforeach; ?>

        <?php endforeach; ?>
    </div>
        <div id="mySmallModalLabel" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        Add an event
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <table id="timetable" class="table table-bordered timetable">
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
                    <td data-toggle="modal" data-target="#mySmallModalLabel"></td>
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
@endsection