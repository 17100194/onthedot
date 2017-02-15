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
            <div class="ttElement" style="padding: 5px; text-align: center; height: <?=$course->height?>px; top: <?= 78+$course->startingHeight?>px; left:<?=$left?>px;">
                <label style="color: white;"><?=$course->name?></label>
            </div>
            <?php endforeach; ?>

        <?php endforeach; ?>
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
                    <th>8AM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>9AM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>10AM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>11AM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>12PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>1PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>2PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>3PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>4PM</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>5PM</th>
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