@extends('layouts.sidemenu')

@section('main')
    <h1>View Notification</h1>
    <hr>

    @if ($type == 'group-pending')

            <div class="alert alert-success" style="display:none;">
                <strong>Request Accepted!</strong> It will appear as soon as the participant agrees.
            </div>
            <div class="row" id="request_<?= $req->requestid ?>">
                <div class="col-sm-6 center-block">
                    <div style="padding: 15px; background: #e2e2e2; border-radius: 5px; margin: 3px; margin-bottom: 10px;">
                        Group name: <?= $req->groupname ?>
                        <br>
                        Sent by: <?= $req->username . ' - ' . $req->campusid ?>
                        <br>
                        Sent on: <?= $req->created_on ?>
                    </div>
                </div>
                <div class="col-sm-3 center-block">
                    <button type="button" class="btn btn-success accept-group-request" data-placement="request_<?= $req->requestid ?>" style="margin: 20px auto; display: block;">Accept</button>
                </div>
                <div class="col-sm-3 center-block">
                    <div class="alert alert-warning" style="display:none;">
                        <strong>Request Rejected!</strong> The rejection reason will be shown on their profile.
                    </div>
                    <button type="button" class="btn btn-warning decline-group-request" data-placement="request_<?= $req->requestid ?>" style="margin: 20px auto; display: block;">Reject</button>
                </div>
            </div>

    @endif


    @if ($type == 'meeting-request')

        <div class="alert alert-success" style="display:none;">
            <strong>Meeting Request Send!</strong> It will appear as soon as the participant agrees.
        </div>
        <div class="row" id="request_<?= $req->meetingid ?>">
            <div class="col-md-6 center-block">
                <div style="padding: 15px; background: #e2e2e2; border-radius: 5px; margin: 3px; margin-bottom: 10px;">
                    Meeting requested by: <?= $req->name ?>
                    <br>
                    Timing: <?= $req->time ?>
                    <br>
                    Date: <?= $req->date ?>
                </div>
            </div>
            <div class="col-md-3 center-block">
                <button type="button" class="btn btn-success accept-request" data-placement="request_<?= $req->meetingid ?>" style="margin: 20px auto; display: block;">Accept</button>
            </div>
            <div class="col-md-3 center-block">
                <div class="alert alert-warning" style="display:none;">
                    <strong>Meeting Rejected!</strong> The rejection reason will be shown on their profile.
                </div>
                <button type="button" class="btn btn-warning decline-request" data-placement="request_<?= $req->meetingid ?>" data-toggle="modal" data-target="#reject_<?= $req->meetingid ?>" style="margin: 20px auto; display: block;">Reject/Reschedule</button>
                <div id="reject_<?= $req->meetingid ?>" class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h4 class="text-center">Rejection Reason</h4>
                                <textarea style="width: 90%; height: 100px; margin: 10px;"></textarea>
                                <button data-meetingid="<?= $req->meetingid ?>" type="button" class="reject-btn btn btn-danger" data-placement="" style="margin: 20px auto; margin-top: 0px; display: block;">Reject</button>
                                <hr/>
                                <h4 class="text-center">You may also ask for the meeting to be rescheduled</h4>
                                <button type="button" class="btn btn-primary" data-placement="" style="margin: 20px auto; display: block;">Reschedule</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif


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
