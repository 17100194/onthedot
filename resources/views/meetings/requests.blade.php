@extends('layouts.sidemenu')

@section('main')
    <div class="row">
        <div class="col-md-6">
            <h4><a>Meeting Requests (<?=count($requests)?>)</a></h4>
            <hr>
            @if (count($requests) > 0)
                <ul style="list-style: none;">
                    @foreach($requests as $request)
                        <li>
                            <div class="alert alert-success" style="display:none;">
                                <strong>Meeting Request Accepted!</strong>
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
                                        <strong>Meeting Request Rejected!</strong> The rejection reason will be shown on their profile.
                                    </div>
                                    <button type="button" class="btn btn-warning decline-request" data-placement="request_<?= $request->meetingid ?>" data-toggle="modal" data-target="#reject_<?= $request->meetingid ?>" style="margin: 20px auto; display: block;">Reject/Reschedule</button>
                                    <div id="reject_<?= $request->meetingid ?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
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
                <h4>No requests at the moment</h4>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $(".accept-request").click(function(){
            var t = $(this);
            var tp = t.parents('li');
            console.log(t.data('placement').replace('request_', ''));
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
        });
    </script>
@endsection