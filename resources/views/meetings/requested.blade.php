@extends('layouts.sidemenu')

@section('main')
<div class="row scheduled">
    <div class="col-md-12">
        <h4 style="text-align: center;"><a>My Requested Meetings</a></h4>
        <hr>
        @if(session('message'))
        <div class="alert alert-success">
            {{ session()->pull('message') }}
        </div>
        @endif
        @if (count($meetings) > 0)
        <ul style="list-style: none; padding-left: 0px;">
            @foreach($meetings as $meeting)
            @if ($meeting->status == 'pending')
            <li style="display: inline-block; width: 45%;">
                <div id="meeting_<?= $meeting->id ?>" class="notification-box" style="position: relative;">
                    <button data-toggle="modal" data-target="#dropModal_<?= $meeting->id?>" class="hover-action btn btn-danger drop">Cancel <i class="fa fa-window-close fa-lg" aria-hidden="true"></i></button>
                    Status: <?= $meeting->status ?>
                    <br>
                    Meeting with: <?= $meeting->name ?>
                    <br>
                    Meeting time: <?= $meeting->time ?>
                    <br>
                    Meeting day: <?= $meeting->day ?>
                    <br>
                    Meeting date: <?= $meeting->date ?>
                </div>
                <div class="modal fade" id="dropModal_<?= $meeting->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="text-align: center;">
                                <h2>Are you sure you want to cancel this request?</h2>
                            </div>
                            <div class="modal-body">
                                <div class="row" style="text-align: center;">
                                    <div class="col-md-6">
                                        <button id="yes_<?= $meeting->id?>" class="button_sliding_bg_2 yes">Yes</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button id="no_<?= $meeting->id?>" class="button_sliding_bg_2 no">No</button>
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
        <p style="margin-left: 20px;">You have sent no meeting requests</p>
        @endif
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

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
//                    alert(data);
                    location.reload();
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
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