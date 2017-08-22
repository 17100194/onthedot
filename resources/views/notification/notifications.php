<?php if (count($notifications) == 0):?>
    <div class="space"></div>
    <p><i class="fa fa-ban fa-5x"></i></p>
    <p class="lead">No Notifications at the moment</p>
<?php endif?>
<?php foreach ($notifications as $notification):?>
    <?php if ($notification->type == 'group-request'):?>
        <a href="<?=url('/group/requests')?>">
            <div class="notification-block" <?php if ($notification->seen == 0):?>style="background: mintcream;"<?php endif?>>
                <p class="no-margin no-padding"><span class="col-xs-2" style="padding-top: 10px;"><i class="fa <?php if ($notification->type == 'group' || $notification->type == 'group-request'):?>fa-users fa-3x<?php else:?>fa-handshake-o fa-3x<?php endif?>"></i></span><span class="col-xs-10 text-left"><?php echo $notification->notification_content?><br><i class="fa fa-clock-o"></i> <span style="color: #1abc9c;"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_on))->diffForHumans()?></span></span></p>
                <hr class="no-margin">
            </div>
        </a>
    <?php elseif ($notification->type == 'meeting-request'):?>
        <a href="<?=url('/meetings/requests')?>">
            <div class="notification-block" <?php if ($notification->seen == 0):?>style="background: mintcream;"<?php endif?>>
                <p class="no-margin no-padding"><span class="col-xs-2" style="padding-top: 10px;"><i class="fa <?php if ($notification->type == 'group' || $notification->type == 'group-request'):?>fa-users fa-3x<?php else:?>fa-handshake-o fa-3x<?php endif?>"></i></span><span class="col-xs-10 text-left"><?php echo $notification->notification_content?><br><i class="fa fa-clock-o"></i> <span style="color: #1abc9c;"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_on))->diffForHumans()?></span></span></p>
                <hr class="no-margin">
            </div>
        </a>
    <?php else:?>
        <div class="notification-block" <?php if ($notification->seen == 0):?>style="background: mintcream;"<?php endif?>>
            <p class="no-margin no-padding"><span class="col-xs-2" style="padding-top: 10px;"><i class="fa <?php if ($notification->type == 'group' || $notification->type == 'group-request'):?>fa-users fa-3x<?php else:?>fa-handshake-o fa-3x<?php endif?>"></i></span><span class="col-xs-10 text-left"><?php echo $notification->notification_content?><br><i class="fa fa-clock-o"></i> <span style="color: #1abc9c;"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_on))->diffForHumans()?></span></span></p>
            <hr class="no-margin">
        </div>
    <?php endif;?>
<?php endforeach;?>
<?php echo $notifications->render();?>
<script>
    $('#notifications').find('.pagination').hide();
</script>
