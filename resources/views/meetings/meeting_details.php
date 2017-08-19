<?php
use App\Http\Controllers\MeetingsController;
?>
<input type="hidden" value="<?php echo strtotime($starttime); ?>" class="starttime">
<input type="hidden" value="<?php echo $endtime; ?>" class="endtime">
<div class="modal-header">
    <div class="hr-title center">
        <abbr>Meeting Details</abbr>
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="text-left">
                <div class="col-sm-6">
                    <h4><span class="label label-info">With</span> <?= $meeting->name?></h4>
                </div>
                <div class="col-sm-6">
                    <h4><span class="label label-info">Date</span> <?= $meeting->date?></h4>
                </div>
                <div class="col-sm-6">
                    <h4><span class="label label-info">Time</span> <?= $meeting->time?></h4>
                </div>
                <div class="col-sm-6">
                    <h4><span class="label label-info">Day</span> <?= $meeting->day?></h4>
                </div>
                <div class="space"></div>
                <div class="hr-title center">
                    <abbr>Countdown to Meeting</abbr>
                </div>
                <div class="countdown rectangle small" data-countdown="<?= $starttime?>"></div>
                <div class="space"></div>
                <div class="hr-title center">
                    <abbr>Meeting Status</abbr>
                </div>
                <div id="status">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="row">
        <div class="col-sm-12">
            <button type="button" data-id="<?= $meeting->id?>" class="btn btn-outline cancel">Cancel</button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var starttime = $('.starttime').val();
        var endtime = $('.endtime').val();
        var $countdownTimer = $('.countdown');
        if ($countdownTimer.length > 0) {
            setTimeout(function () {
                $('[data-countdown]').each(function () {
                    var $this = $(this), finalDate = $(this).data('countdown');
                    $this.countdown(finalDate, function (event) {
                        $this.html(event.strftime('<div class="countdown-container"><div class="countdown-box"><div class="number">%-D</div><span>Day%!d</span></div>' + '<div class="countdown-box"><div class="number">%H</div><span>Hours</span></div>' + '<div class="countdown-box"><div class="number">%M</div><span>Minutes</span></div>' + '<div class="countdown-box"><div class="number">%S</div><span>Seconds</span></div></div>'));
                    });
                });
            },100);
        }
        var now = 0;
        now = Math.round(Date.now()/1000);
        if (now < starttime){
            $('#status').html('<h4 class="text-center text-info"><i class="fa fa-info-circle"></i> Not Started</h4>');
            $('.modal-footer').show();
        }
        else if (now >= starttime && now <= endtime){
            $('#status').html('<h4 class="text-center text-info"><i class="fa fa-info-circle"></i> In Progress</h4>');
            $('.modal-footer').hide();
        }
        else {
            $('#status').html('<h4 class="text-center text-info"><i class="fa fa-info-circle"></i> Finished</h4>');
            $('.modal-footer').hide();
        }
        var interval = setInterval(function () {
            now = Math.round(Date.now()/1000);
            if (now < starttime){
                $('#status').html('<h4 class="text-center text-info"><i class="fa fa-info-circle"></i> Not Started</h4>');
                $('.modal-footer').show();
            }
            else if (now >= starttime && now <= endtime){
                $('#status').html('<h4 class="text-center text-info"><i class="fa fa-info-circle"></i> In Progress</h4>');
                $('.modal-footer').hide();
            }
            else {
                $('#status').html('<h4 class="text-center text-info"><i class="fa fa-info-circle"></i> Finished</h4>');
                $('.modal-footer').hide();
            }
        },1000);
        $('.modal-dialog').parent().on('hidden.bs.modal', function(e){clearInterval(interval); });
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
