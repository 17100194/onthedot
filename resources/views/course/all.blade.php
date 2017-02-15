@extends('layouts.sidemenu')

@section('main')
    <div class="row courses">
        <div class="col-md-12">
            <h4 style="margin-left: 20px;"><a>My Courses</a></h4>
            <hr>
            @if (count($courses) > 0)
                <ul style="list-style: none; padding-left: 0px;">
                    @foreach($courses as $course)
                        <li style="display: inline-block; width: 49.5%;">
                            <div class="notification-box">
                                Course: <?= $course->name ?>
                                <br>
                                <?php
                                $strt = date('h:iA', strtotime(explode('-', $course->timing)[0]));
                                $endt = date('h:iA', strtotime(explode('-', $course->timing)[1]));

                                ?>
                                Timing: <?= $strt .' - '.$endt ?>
                                <br>
                                Section: <?= $course->section ?>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="margin-left: 20px;">You have no meetings scheduled at the moment</p>
            @endif
        </div>
    </div>
@endsection