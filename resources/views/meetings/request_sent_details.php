<?php
use App\Http\Controllers\MeetingsController;
?>

<div class="modal-header">
    <div class="hr-title center">
        <abbr>Meeting Request Details</abbr>
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="text-center">
                <h4><span class="label label-info">To</span> <?= $meeting->name?></h4>
                <h4><span class="label label-info">Date</span> <?= $meeting->date?></h4>
                <h4><span class="label label-info">Day</span> <?= $meeting->day?></h4>
                <h4><span class="label label-info">Time</span> <?= $meeting->time?></h4>
                <div class="hr-title center">
                    <abbr>Cancel Request</abbr>
                </div>
                <button type="button" data-id="<?=$meeting->id?>" class="btn btn-shadow cancel"> Cancel Request</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.cancel').on('click', function () {
            var meetingid = $(this).data('id');
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
                    location.reload();
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>