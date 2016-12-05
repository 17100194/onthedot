@extends('layouts.app')

@section('content')
    <div class="container" style="margin: 0px; width: 100%;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-body" style="background: url(<?= asset('images/cover.png') ?>); height: 500px; background-size: cover;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-center" style="color: white; margin-top: 150px">
                                    <h2>SchedulerApp</h2>
                                    <h4>Scheduling meetings made 2x faster.</h4>
                                    <h4>Meetings now scheduled in minutes rather than days</h4>
                                    <form method="get" action="{{ action('MeetingsController@q') }}" class='form navbar-form navbar-center searchform'>
                                        <input name="search" style="width: 50%;" class="form-control" placeholder="Search for a user or a group...">
                                        <input type="submit" class="btn btn-primary" value="Search"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
