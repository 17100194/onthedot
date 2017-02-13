@extends('layouts.sidemenu')

@section('main')
<div class="row scheduled">
    <div class="col-md-12">
        <h4><a>My Scheduled Meetings</a></h4>
        <hr>
        @if (count($meetings) > 0)
            <ul style="list-style: none; padding-left: 0px;">
                @foreach($meetings as $meeting)
                    @if ($meeting->status != 'pending')
                        <li style="display: inline-block; width: 49.5%;">
                            <div class="notification-box">
                                Meeting with: <?= $meeting->name ?>
                                <br>
                                Meeting time: <?= $meeting->time ?>
                                <br>
                                Meeting day: <?= $meeting->day ?>
                                <br>
                                Meeting date: <?= $meeting->date ?>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        @else
            <p style="margin-left: 20px;">You have no meetings scheduled at the moment</p>
        @endif
    </div>
</div>
@endsection