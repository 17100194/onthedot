@extends('layouts.sidemenu')

@section('main')
    <div class="row scheduled">
        <div class="col-md-12">
            <h4><a>My Groups</a></h4>
            <hr>
            @if (count($groups) > 0)
                <ul style="list-style: none; padding-left: 0px;">
                    @foreach($groups as $group)
                        <li style="display: inline-block; width: 49.5%;">
                            <div class="notification-box">
                                Group Name: <?= $group->groupname ?>
                                <br>
                                By: <?= $group->creator ?>
                                <br>
                                Created On: <?= $group->created_on ?>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="margin-left: 20px;">You have no groups at the moment</p>
            @endif
        </div>
    </div>
@endsection