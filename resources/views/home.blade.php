@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome back!</div>

                <div class="panel-body">
                    <form method="get" action="{{ action('MeetingsController@q') }}" class='form navbar-form navbar-right searchform'>
                        <input name="search" class="form-control" placeholder="search for a user">
                        <input type="submit" class="btn btn-primary" value="Search"/>
                    </form>
                    <h4><a>My Courses</a></h4>
                    @if (count($courses) > 0)
                        <ul style="list-style: none;">
                            @foreach($courses as $course)
                                <li>
                                    <div style="padding: 15px; background: #e2e2e2; border-radius: 5px; margin: 3px; width: 35%; margin-bottom: 10px;">
                                        Course: <?= $course->name ?>
                                        <br>
                                        Timing: <?= $course->timing ?>
                                        <br>
                                        Section: <?= $course->section ?>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <hr/>
                <h4 style="margin-left: 20px;"><a>My Meetings</a></h4>
                @if (count($meetings) > 0)
                    <ul style="list-style: none;">
                        @foreach($meetings as $meeting)
                            <li>
                                <div style="padding: 15px; background: #e2e2e2; border-radius: 5px; margin: 3px; width: 35%; margin-bottom: 10px;">
                                    Meeting with: <?= $meeting->name ?>
                                    <br>
                                    Meeting time: <?= $meeting->time ?>
                                    <br>
                                    Meeting day: <?= $meeting->day ?>
                                    <br>
                                    Meeting date: <?= $meeting->date ?>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p style="margin-left: 20px;">You have no meetings scheduled at the moment</p>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
