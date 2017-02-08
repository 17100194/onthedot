@extends('layouts.sidemenu')

@section('main')
<div class="row">
    <div class="col-md-12">
        <h4 style="margin-left: 20px;"><a>My Scheduled Meetings</a></h4>
        <hr>
        @if (count($meetings) > 0)
            <ul style="list-style: none; padding-left: 0px;">
                @foreach($meetings as $meeting)
                    @if ($meeting->status != 'pending')
                        <li style="display: inline-block; width: 49.5%;">
                            <div style="padding: 15px; background: #e2e2e2; border-radius: 5px; margin: 3px; margin-bottom: 10px;">
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