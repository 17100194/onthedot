@extends('layouts.sidemenu')

@section('main')
    <h4 style="text-align: center;"><a>My Timetable</a></h4>
    <hr>
    @if(session('message'))
        <div class="alert alert-success">
            {{ session()->pull('message') }}
        </div>
    @endif
    <div class="cd-schedule loading">
        <div class="timeline">
            <ul>
                <li><span>09:00</span></li>
                <li><span>09:30</span></li>
                <li><span>10:00</span></li>
                <li><span>10:30</span></li>
                <li><span>11:00</span></li>
                <li><span>11:30</span></li>
                <li><span>12:00</span></li>
                <li><span>12:30</span></li>
                <li><span>13:00</span></li>
                <li><span>13:30</span></li>
                <li><span>14:00</span></li>
                <li><span>14:30</span></li>
                <li><span>15:00</span></li>
                <li><span>15:30</span></li>
                <li><span>16:00</span></li>
                <li><span>16:30</span></li>
                <li><span>17:00</span></li>
                <li><span>17:30</span></li>
                <li><span>18:00</span></li>
            </ul>
        </div> <!-- .timeline -->

        <div class="events">
            <ul>
                <li class="events-group">
                    <div class="top-info"><span>Monday</span></div>
                    <ul>
                        <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
                        <?php
                        switch ($day) {
                            case "Monday":?>
                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-1">
                                <a href="#0">
                                    @if($course->type != 'meeting')
                                        <em class="event-name"><?= $course->name?></em>
                                    @else
                                        <em class="event-name">Meeting</em>
                                    @endif
                                </a>
                            </li>
                                <?php break;
                            case "Tuesday":
                                break;
                            case "Wednesday":
                                break;
                            case "Thursday":
                                break;
                            case "Friday":
                                break;
                        }
                        ?>
                            <?php endforeach; ?>
                            <?php endforeach; ?>
                    </ul>
                </li>
                <li class="events-group">
                    <div class="top-info"><span>Tuesday</span></div>
                    <ul>
                        <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
                        <?php
                        switch ($day) {
                        case "Monday":
                            break;
                        case "Tuesday":?>
                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-2">
                                <a href="#0">
                                    @if($course->type != 'meeting')
                                        <em class="event-name"><?= $course->name?></em>
                                    @else
                                        <em class="event-name">Meeting</em>
                                    @endif
                                </a>
                            </li>
                            <?php break;
                        case "Wednesday":
                            break;
                        case "Thursday":
                            break;
                        case "Friday":
                            break;
                        }
                        ?>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li class="events-group">
                    <div class="top-info"><span>Wednesday</span></div>
                    <ul>
                        <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
                        <?php
                        switch ($day) {
                        case "Monday":
                            break;
                        case "Tuesday":
                            break;
                        case "Wednesday":?>
                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-3">
                                <a href="#0">
                                    @if($course->type != 'meeting')
                                        <em class="event-name"><?= $course->name?></em>
                                    @else
                                        <em class="event-name">Meeting</em>
                                    @endif
                                </a>
                            </li>
                            <?php break;
                        case "Thursday":
                            break;
                        case "Friday":
                            break;
                        }
                        ?>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li class="events-group">
                    <div class="top-info"><span>Thursday</span></div>
                    <ul>
                        <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
                        <?php
                        switch ($day) {
                        case "Monday":
                            break;
                        case "Tuesday":
                            break;
                        case "Wednesday":
                            break;
                        case "Thursday":?>
                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-4">
                                <a href="#0">
                                    @if($course->type != 'meeting')
                                        <em class="event-name"><?= $course->name?></em>
                                    @else
                                        <em class="event-name">Meeting</em>
                                    @endif
                                </a>
                            </li>
                            <?php break;
                        case "Friday":
                            break;
                        }
                        ?>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="events-group">
                    <div class="top-info"><span>Friday</span></div>
                    <ul>
                        <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
                        <?php
                        switch ($day) {
                        case "Monday":
                            break;
                        case "Tuesday":
                            break;
                        case "Wednesday":
                            break;
                        case "Thursday":
                            break;
                        case "Friday":?>
                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-5">
                                <a href="#0">
                                    @if($course->type != 'meeting')
                                        <em class="event-name"><?= $course->name?></em>
                                    @else
                                        <em class="event-name">Meeting</em>
                                    @endif
                                </a>
                            </li>
                            <?php break;
                        }
                        ?>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="event-modal">
            <header class="header">
                <div class="content">
                    <span class="event-date"></span>
                    <h3 class="event-name"></h3>
                </div>

                <div class="header-bg"></div>
            </header>

            <div class="body">
                <div class="event-info"></div>
                <div class="body-bg"></div>
            </div>

            <a href="#0" class="close">Close</a>
        </div>

        <div class="cover-layer"></div>
    </div>
    <script>
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