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
                            <div id="meeting_<?= $meeting->id ?>" class="notification-box" style="position: relative;">
                                <button style="position: absolute; top: 0px; right: 0px; padding: 3px; display: none;" data-toggle="modal" data-target="#dropModal_<?= $meeting->id?>" class="btn btn-danger drop">Cancel <i class="fa fa-window-close fa-lg" aria-hidden="true"></i></button>
                                Meeting with: <?= $meeting->name ?>
                                <br>
                                Meeting time: <?= $meeting->time ?>
                                <br>
                                Meeting day: <?= $meeting->day ?>
                                <br>
                                Meeting date: <?= $meeting->date ?>
                            </div>
                            <div class="modal fade" id="dropModal_<?= $meeting->id?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="text-align: center;">
                                            <h2>Are you sure you want to cancel this meeting?</h2>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row" style="text-align: center;">
                                                <div class="col-md-6">
                                                    <button id="yes_<?= $meeting->id?>" class="btn btn-success btn-lg yes">Yes</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button id="no_<?= $meeting->id?>" class="btn btn-warning btn-lg no">No</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('.notification-box').hover(function () {
            $(this).find('.drop').fadeIn();
        }, function () {
            $(this).find('.drop').fadeOut();
        });
        $('.yes').on('click', function () {
            var meetingid = $(this).attr('id').split('_')[1];
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./cancelMeeting",
                data: {
                    meetingid: meetingid
                },
                success: function(data) {
                    alert(data);
//                    location.reload();
                },
                error: function (xhr, status) {
//                    console.log(status);
//                    console.log(xhr.responseText);
                }
            });
        });
        $('.no').on('click', function () {
            var meetingid = $(this).attr('id').split('_')[1];
            jQuery.noConflict();
            $('#dropModal_'+meetingid).modal('hide');
        });
        if($('.alert')) {
            $('.alert').show();
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 3000);
        }
    });
</script>
@endsection