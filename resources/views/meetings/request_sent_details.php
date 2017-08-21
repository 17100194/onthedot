<?php
use App\Http\Controllers\MeetingsController;
?>
<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="hr-title center">
                <abbr>Meeting Request Details</abbr>
            </div>
            <div class="text-left">
                <div class="col-sm-6">
                    <h4><span class="label label-info">To</span> <?php if ($meeting->first()->type == 'group'):?>Group<?php else:?><?=$meeting->first()->name?><?php endif;?></h4>
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
                                <td><?php if ($user->status_meeting == 'accepted'):?><label class="label label-success">Accepted</label><?php elseif ($user->status_meeting == 'rejected'):?><label class="label label-default">Rejected</label><?php else:?><label class="label label-info">Pending</label><?php endif;?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" data-id="<?=$meeting->first()->id?>" class="btn btn-shadow cancel"> Cancel Request</button>
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