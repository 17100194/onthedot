@extends('layouts.sidemenu')

@section('main')
    <div class="row">
        <div class="col-md-6">
            <h4><a>Meeting Requests <?php if(count($requests) > 0): ?>(<?=count($requests)?>)<?php endif; ?></a></h4>
            @if (count($requests) > 0)
                <ul style="list-style: none;">
                    @foreach($requests as $request)
                        <li>
                            <div class="alert alert-success" style="display:none;">
                                <strong>Meeting Request Send!</strong> It will appear as soon as the participant agrees.
                            </div>
                            <div class="row" id="request_<?= $request->meetingid ?>">
                                <div class="col-md-6 center-block">
                                    <div style="padding: 15px; background: #e2e2e2; border-radius: 5px; margin: 3px; margin-bottom: 10px;">
                                        Meeting requested by: <?= $request->name ?>
                                        <br>
                                        Timing: <?= $request->time ?>
                                        <br>
                                        Date: <?= $request->date ?>
                                    </div>
                                </div>
                                <div class="col-md-3 center-block">
                                    <button type="button" class="btn btn-success accept-request" data-placement="request_<?= $request->meetingid ?>" style="margin: 20px auto; display: block;">Accept</button>
                                </div>
                                <div class="col-md-3 center-block">
                                    <div class="alert alert-warning" style="display:none;">
                                        <strong>Meeting Rejected!</strong> The rejection reason will be shown on their profile.
                                    </div>
                                    <button type="button" class="btn btn-warning decline-request" data-placement="request_<?= $request->meetingid ?>" data-toggle="modal" data-target="#reject_<?= $request->meetingid ?>" style="margin: 20px auto; display: block;">Reject/Reschedule</button>
                                    <div id="reject_<?= $request->meetingid ?>" class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h4 class="text-center">Rejection Reason</h4>
                                                    <textarea style="width: 90%; height: 100px; margin: 10px;"></textarea>
                                                    <button data-meetingid="<?= $request->meetingid ?>" type="button" class="reject-btn btn btn-danger" data-placement="" style="margin: 20px auto; margin-top: 0px; display: block;">Reject</button>
                                                    <hr/>
                                                    <h4 class="text-center">You may also ask for the meeting to be rescheduled</h4>
                                                    <button type="button" class="btn btn-primary" data-placement="" style="margin: 20px auto; display: block;">Reschedule</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                        </li>
                    @endforeach
                </ul>
            @else
                <h4>No requests</h4>
            @endif
        </div>
        <div class="col-md-6">
            <h4><a>Group Requests <?php if(count($groupRequestPending) > 0): ?>(<?=count($groupRequestPending)?>)<?php endif; ?></a></h4>
            @if (count($groupRequestPending) > 0)
                <ul style="list-style: none;">
                    @foreach($groupRequestPending as $groupRequest)
                        <li>
                            <div class="alert alert-success" style="display:none;">
                                <strong>Request Accepted!</strong> It will appear as soon as the participant agrees.
                            </div>
                            <div class="row" id="request_<?= $groupRequest->requestid ?>">
                                <div class="col-sm-6 center-block">
                                    <div style="padding: 15px; background: #e2e2e2; border-radius: 5px; margin: 3px; margin-bottom: 10px;">
                                        Group name: <?= $groupRequest->groupname ?>
                                        <br>
                                        Sent by: <?= $groupRequest->username . ' - ' . $groupRequest->campusid ?>
                                        <br>
                                        Sent on: <?= $groupRequest->created_on ?>
                                    </div>
                                </div>
                                <div class="col-sm-3 center-block">
                                    <button type="button" class="btn btn-success accept-group-request" data-placement="request_<?= $groupRequest->requestid ?>" style="margin: 20px auto; display: block;">Accept</button>
                                </div>
                                <div class="col-sm-3 center-block">
                                    <div class="alert alert-warning" style="display:none;">
                                        <strong>Request Rejected!</strong> The rejection reason will be shown on their profile.
                                    </div>
                                    <button type="button" class="btn btn-warning decline-group-request" data-placement="request_<?= $groupRequest->requestid ?>" style="margin: 20px auto; display: block;">Reject</button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <h4>No requests</h4>
            @endif
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <h4><a>My Courses</a></h4>
            @if (count($courses) > 0)
                <ul style="list-style: none;">
                    @foreach($courses as $course)
                        <li>
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
            @endif
        </div>
        <div class="col-md-4">
            <h4 style="margin-left: 20px;"><a>My Meetings</a></h4>
            @if (count($meetings) > 0)
                <ul style="list-style: none;">
                    @foreach($meetings as $meeting)
                        @if ($meeting->status != 'pending')
                            <li>
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
        <div class="col-md-4">
            <h4><a>My Groups</a></h4>
            @if (count($groups) > 0)
                <ul style="list-style: none;">
                    @foreach($groups as $group)
                        <li>
                            <div style="padding: 15px; background: #e2e2e2; border-radius: 5px; margin: 3px; margin-bottom: 10px;">
                                Group Name: <?= $group->groupname ?>
                                <br>
                                By: <?= $group->creator ?>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $(".accept-request").click(function(){
            var t = $(this);
            var tp = t.parents('li');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./accept",
                data: {
                    meetingid: t.data('placement').replace('request_', '')
                },
                success: function(data) {
                    $('#'+t.data('placement')).hide();
                    t.parents('.row').siblings('.alert').show();
                    window.setTimeout(function () {
                        t.parents('.row').siblings('.alert').fadeTo(500, 0).slideUp(500, function () {
                            tp.remove();
                        });
                    }, 2000);
                },
                error: function (xhr, status) {
                }
            });
        });

        $(".accept-group-request").click(function(){
            var t = $(this);
            var tp = t.parents('li');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./acceptRequest",
                data: {
                    requestid: t.data('placement').replace('request_', '')
                },
                success: function(data) {
                    $('#'+t.data('placement')).hide();
                    t.parents('.row').siblings('.alert').show();
                    window.setTimeout(function () {
                        t.parents('.row').siblings('.alert').fadeTo(500, 0).slideUp(500, function () {
                            tp.remove();
                        });
                    }, 2000);
                },
                error: function (xhr, status) {
                }
            });
        });

        $('.reject-group-request').click(function() {
            var t = $(this);
            var rId = t.data('placement').replace('request_', '')

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./rejectRequest",
                data: {
                    requestid: rId
                },
                success: function(data) {
                    $('#reject_'+mId).hide();
                    t.parents('.center-block').siblings('.alert').show();
                    window.setTimeout(function () {
                        t.parents('.center-block').siblings('.alert').fadeTo(500, 0).slideUp(500, function () {
                            tp.remove();
                        });
                    }, 2000);
                },
                error: function (xhr, status) {
                }
            });
        })

        $('.reject-btn').click(function() {
            var t = $(this);
            var msg = t.siblings('textarea').val();
            var mId = t.attr('data-meetingid');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./reject",
                data: {
                    meetingid: mId,
                    message: msg
                },
                success: function(data) {
                    t.parents('.modal').modal('toggle');
                    $('.modal-backdrop').hide();
                    $('#reject_'+mId).hide();
                    t.parents('.center-block').siblings('.alert').show();
                    window.setTimeout(function () {
                        t.parents('.center-block').siblings('.alert').fadeTo(500, 0).slideUp(500, function () {
                            tp.remove();
                        });
                    }, 2000);
                },
                error: function (xhr, status) {
                }
            });
        })
    </script>
@endsection
