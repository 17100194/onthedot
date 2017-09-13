<?php
use App\Http\Controllers\MeetingsController;
use Illuminate\Support\Facades\Auth;
?>
<input type="hidden" value="<?php echo strtotime($starttime); ?>" class="starttime">
<input type="hidden" value="<?php echo $endtime; ?>" class="endtime">
<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="hr-title center">
                <abbr>Meeting Details</abbr>
            </div>
            <div class="text-left">
                <div class="col-sm-6">
                    <h4><span class="label label-info">With</span> <?php if ($meeting->first()->type == 'group'):?>Group<?php else:?><?=$meeting->first()->name?><?php endif;?></h4>
                </div>
                <div class="col-sm-6">
                    <h4><span class="label label-info">Date</span> <?= $meeting->first()->date?></h4>
                </div>
                <div class="col-sm-6">
                    <h4><span class="label label-info">Time</span> <?= $meeting->first()->time?></h4>
                </div>
                <div class="col-sm-6">
                    <h4><span class="label label-info">Day</span> <?= $meeting->first()->day?></h4>
                </div>
                <div class="col-sm-6">
                    <h4><span class="label label-info">Venue</span> <?= $meeting->first()->venue?></h4>
                </div>
                <?php if($meeting->first()->type == 'group'):?>
                <div class="space"></div>
                <div class="hr-title center">
                    <abbr>Participants</abbr>
                </div>
                    <table class="table table-striped">
                        <thead class="background-colored text-light">
                        <th>Name</th>
                        <th>Campus ID</th>
                        <th>Type</th>
                        <th>Status</th>
                        </thead>
                        <tbody>
                        <?php foreach ($meeting->users as $user):?>
                            <tr>
                                <td><?=$user->name?></td>
                                <td><?=$user->campusid?></td>
                                <td><?=$user->type?></td>
                                <td><?php if ($user->status_meeting == 'accepted'):?><label class="label label-success">Accepted</label><?php elseif ($user->status_meeting == 'rejected'):?><label class="label label-default">Rejected</label><?php elseif ($user->status_meeting == 'cancelled'):?><label class="label label-primary">Cancelled</label><?php else:?><label class="label label-info">Pending</label><?php endif;?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                <?php endif;?>
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
            <button type="button" data-id="<?= $meeting->first()->id?>" class="btn btn-outline cancel">Cancel Meeting</button>
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
                beforeSend: function () {
                  $(this).parents('.modal-content').LoadingOverlay('show');
                },
                success: function(data) {
                    $(this).parents('.modal-content').LoadingOverlay('hide',true);
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
