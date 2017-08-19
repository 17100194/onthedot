<style>

</style>
<?php if (count($notifications) == 0):?>
    <div class="space"></div>
    <p><i class="fa fa-ban fa-5x"></i></p>
    <p class="lead">No Notifications at the moment</p>
<?php endif?>
<?php foreach ($notifications as $notification):?>
    <div class="notification-block"<?php if ($notification->seen == 0):?>style="background: mintcream;"<?php endif?>>
        <p class="no-margin no-padding"><span class="col-xs-2" style="padding-top: 10px;"><i class="fa <?php if ($notification->type == 'group'):?>fa-users fa-3x<?php else:?>fa-handshake-o fa-3x<?php endif?>"></i></span><span class="col-xs-10 text-left"><?php echo $notification->notification_content?><br><i class="fa fa-clock-o"></i> <span style="color: #1abc9c;"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_on))->diffForHumans()?></span></span></p>
        <hr class="no-margin">
    </div>
<?php endforeach;?>
<?php echo $notifications->render();?>
<script>
    $('#notifications').find('.pagination').hide();
</script>
