@extends('layouts.sidemenu')

@section('main')
    <div class="row">
        <div class="col-md-12">
            <h4 style="margin-left: 20px;"><a>My Courses</a></h4>
            <hr>
            @if (count($courses) > 0)
                <ul style="list-style: none; padding-left: 0px;">
                    @foreach($courses as $course)
                        <li style="display: inline-block; width: 49.5%;">
                            <div style="padding: 15px; background: #e2e2e2; border-radius: 5px; margin: 3px; margin-bottom: 10px;">
                                Course: <?= $course->name ?>
                                <br>
                                Timing: <?= $course->timing ?>
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