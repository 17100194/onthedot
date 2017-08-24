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
                <div class="col-sm-6"><h4><span class="label label-info">From</span> <?= $request->name?></h4></div>
                <div class="col-sm-6"><h4><span class="label label-info">Date</span> <?= $request->date?></h4></div>
                <div class="col-sm-6"><h4><span class="label label-info">Time</span> <?= $request->time?></h4></div>
                <div class="col-sm-6"><h4><span class="label label-info">Venue</span> <?= $request->venue?></h4></div>
            </div>
        </div>
    </div>
    <div class="space"></div>
    <div class="row">
        <div class="col-sm-6 text-center">
            <div class="hr-title center">
                <abbr>Available?</abbr>
            </div>
            <p>Accept the request by clicking the button below and let the others know of your availability</p>
            <button type="button" data-id="<?=$request->meetingid?>" class="btn btn-success accept"><i class="fa fa-check"></i> Accept</button>
        </div>
        <div class="col-sm-6 text-center">
            <div class="hr-title center">
                <abbr>Not Available?</abbr>
            </div>
            <div class="form-group">
                <label>Reason?</label>
                <textarea class="form-control" id="reason" name="reason"></textarea>
            </div>
            <button type="button" data-id="<?=$request->meetingid?>" class="btn btn-danger reject"><i class="fa fa-times"></i> Reject</button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".accept").on('click', function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./accept",
                data: {
                    meetingid: $(this).data('id')
                },
                success: function(data) {
                    location.reload();
                },
                error: function (xhr, status) {
                    console.log(xhr);
                    console.log(status);
                }
            });
        });
        $('.reject').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./reject",
                data: {
                    meetingid: $(this).data('id'),
                    reason: $('#reason').val()
                },
                beforeSend:function () {
                    $('.help-block').remove();
                    $('.form-group').removeClass('has-error');
                },
                success: function(data) {
                    if(data.success == false)
                    {
                        var arr = data.errors;
                        $.each(arr, function(index, value)
                        {
                            if (value.length != 0)
                            {
                                $('textarea[name='+index+']').parent().addClass('has-error');
                                $('textarea[name='+index+']').after('<span class="help-block"><strong>'+ value +'</strong></span>');
                            }
                        });
                    } else {
                        location.reload();
                    }
                },
                error: function (xhr, status) {
                }
            });
        });
    });
</script>