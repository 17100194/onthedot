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

                    @if (count($courses) > 0)
                        <ul style="list-style: none;">
                            @foreach($courses as $course)
                                <li>
                                    <div style="padding: 15px;">
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
            </div>
        </div>
    </div>
</div>
@endsection
