<?php
use Illuminate\Support\Facades\Auth;
?>

<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
</div>
<div>
    <div class="hr-title center">
        <abbr>Combined Timetable</abbr>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center">
            <span class="text-shadow-dark text-orange" style="font-size: 2rem;"><?=date('F d,Y', strtotime($monday)).' - '.date('F d,Y', strtotime($friday))?></span> <button class="prevweek btn"><i class="fa fa-arrow-left fa-lg"></i></button><button class="nextweek btn"><i class="fa fa-arrow-right fa-lg"></i></button>
        </div>
        <div class="space"></div>
        <div class="col-sm-6 text-center">
            <h4 class="text-info">Timetable Key:</h4>
            <div>
                <label class="label label-default" style="background: #577F92;">Your Event</label>
                <label class="label label-default" style="background: #443453;">Common Event</label>
                <label class="label label-default" style="background: #A2B9B2;">Users Event</label>
            </div>
        </div>
        <div class="col-sm-6 text-center">
            <div class="space"></div>
            <?php if(Auth::guest()):?>
                <p class="lead text-info">You must be logged in to schedule an appointment with this user</p>
            <?php else:?>
                <button type="button" class="btn btn-default scheduleform">Schedule an appointment</button>
            <?php endif?>
        </div>
    </div>
    <div class="cd-schedule loading">
        <div class="timeline">
            <ul>
                <li><span>09:00</span></li>
                <li><span>09:30</span></li>
                <li><span>10:00</span></li>
                <li><span>10:30</span></li>
                <li><span>11:00</span></li>
                <li><span>11:30</span></li>
                <li><span>12:00</span></li>
                <li><span>12:30</span></li>
                <li><span>13:00</span></li>
                <li><span>13:30</span></li>
                <li><span>14:00</span></li>
                <li><span>14:30</span></li>
                <li><span>15:00</span></li>
                <li><span>15:30</span></li>
                <li><span>16:00</span></li>
                <li><span>16:30</span></li>
                <li><span>17:00</span></li>
                <li><span>17:30</span></li>
                <li><span>18:00</span></li>
            </ul>
        </div> <!-- .timeline -->

        <div class="events">
            <ul>
                <li class="events-group">
                    <input type="hidden" value="<?=$monday?>" id="monday">
                    <div class="top-info monday"><span>Monday<br>(<?=$monday?>)</span></div>
                    <ul>
                        <?php foreach($allevents as $event): ?>
                            <?php foreach($event->days as $day): ?>
                                <?php
                                switch ($day) {
                                    case "Monday":?>
                                        <?php if($event->type == 'meeting'){?>
                                            <?php if($event->date == date('Y-m-d',strtotime($monday))){?>
                                                <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->time)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->time)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                    <a data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                                </li>
                                            <?php }?>
                                        <?php }else{?>
                                            <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->timing)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                <a data-toggle="tooltip" data-placement="top" title data-original-title="<?=$event->name?>"></a>
                                            </li>
                                        <?php }?>
                                        <?php break;
                                    case "Tuesday":
                                        break;
                                    case "Wednesday":
                                        break;
                                    case "Thursday":
                                        break;
                                    case "Friday":
                                        break;
                                }
                                ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="events-group">
                    <input type="hidden" value="<?=$tuesday?>" id="tuesday">
                    <div class="top-info"><span>Tuesday<br>(<?=$tuesday?>)</span></div>
                    <ul>
                        <?php foreach($allevents as $event): ?>
                            <?php foreach($event->days as $day): ?>
                                <?php
                                switch ($day) {
                                    case "Monday":
                                        break;
                                    case "Tuesday":?>
                                        <?php if($event->type == 'meeting'){?>
                                            <?php if($event->date == date('Y-m-d',strtotime($tuesday))){?>
                                                <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->time)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->time)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                    <a data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                                </li>
                                            <?php }?>
                                        <?php }else{?>
                                            <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->timing)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                <a data-toggle="tooltip" data-placement="top" title data-original-title="<?=$event->name?>"></a>
                                            </li>
                                        <?php }?>
                                        <?php break;
                                    case "Wednesday":
                                        break;
                                    case "Thursday":
                                        break;
                                    case "Friday":
                                        break;
                                }
                                ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li class="events-group">
                    <input type="hidden" value="<?=$wednesday?>" id="wednesday">
                    <div class="top-info"><span>Wednesday<br>(<?=$wednesday?>)</span></div>
                    <ul>
                        <?php foreach($allevents as $event): ?>
                            <?php foreach($event->days as $day): ?>
                                <?php
                                switch ($day) {
                                    case "Monday":
                                        break;
                                    case "Tuesday":
                                        break;
                                    case "Wednesday":?>
                                        <?php if($event->type == 'meeting'){?>
                                            <?php if($event->date == date('Y-m-d',strtotime($wednesday))){?>
                                                <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->time)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->time)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                    <a data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                                </li>
                                            <?php }?>
                                        <?php }else{?>
                                            <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->timing)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                <a data-toggle="tooltip" data-placement="top" title data-original-title="<?=$event->name?>"></a>
                                            </li>
                                        <?php }?>
                                        <?php break;
                                    case "Thursday":
                                        break;
                                    case "Friday":
                                        break;
                                }
                                ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li class="events-group">
                    <input type="hidden" value="<?=$thursday?>" id="thursday">
                    <div class="top-info"><span>Thursday<br>(<?=$thursday?>)</span></div>
                    <ul>
                        <?php foreach($allevents as $event): ?>
                            <?php foreach($event->days as $day): ?>
                                <?php
                                switch ($day) {
                                    case "Monday":
                                        break;
                                    case "Tuesday":
                                        break;
                                    case "Wednesday":
                                        break;
                                    case "Thursday":?>
                                        <?php var_dump($day)?>
                                        <?php if($event->type == 'meeting'){?>
                                            <?php if($event->date == date('Y-m-d',strtotime($thursday))){?>
                                                <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->time)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->time)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                    <a data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                                </li>
                                            <?php }?>
                                        <?php }else{?>
                                            <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->timing)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                <a data-toggle="tooltip" data-placement="top" title data-original-title="<?=$event->name?>"></a>
                                            </li>
                                        <?php }?>
                                        <?php break;
                                    case "Friday":
                                        break;
                                }
                                ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="events-group">
                    <input type="hidden" value="<?=$friday?>" id="friday">
                    <div class="top-info"><span>Friday<br>(<?=$friday?>)</span></div>
                    <ul>
                        <?php foreach($allevents as $event): ?>
                            <?php foreach($event->days as $day): ?>
                                <?php
                                switch ($day) {
                                    case "Monday":
                                        break;
                                    case "Tuesday":
                                        break;
                                    case "Wednesday":
                                        break;
                                    case "Thursday":
                                        break;
                                    case "Friday":?>
                                        <?php if($event->type == 'meeting'){?>
                                            <?php if($event->date == date('Y-m-d',strtotime($friday))){?>
                                                <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->time)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->time)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                    <a data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                                </li>
                                            <?php }?>
                                        <?php }else{?>
                                            <li style="visibility: visible;" class="single-event" data-start="<?= date("H:i",strtotime(explode('-',$event->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$event->timing)[1]))?>" data-event="<?php if ($event->who == 'me'):?>event-1<?php elseif($event->who == 'common'):?>event-2<?php else:?>event-3<?php endif;?>">
                                                <a data-toggle="tooltip" data-placement="top" title data-original-title="<?=$event->name?>"></a>
                                            </li>
                                        <?php }?>
                                        <?php break;
                                }
                                ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<script src="<?= asset('js/main.js')?>"></script>
<script>
    $('.modal-dialog').parent().on('hidden.bs.modal', function(e){ $('.scheduleform').popover('hide')});
    var $tooltip = $('[data-toggle="tooltip"]');
    if ($tooltip.length > 0) {
        $('[data-toggle="tooltip"]').tooltip();
    }
    var $popover = $('.group').find('.scheduleform');
    if ($popover.length > 0) {
        $('.group').find('.scheduleform').popover({
            html: true,
            placement: 'bottom',
            content: "<div><div id='status'></div><div class='form-group'><label>Start Time</label><input type='time' class='form-control' id='start' name='start'></div><div class='form-group'><label>Date</label><input type='date' class='form-control' id='date' name='date'></div><div class='form-group'><label>Duration (Minutes)</label><input type='text' class='form-control' id='duration' name='duration'></div><div class='form-group'><label>Venue</label><input type='text' class='form-control' id='venue' name='venue'></div><div class='form-group'><button id='send' class='btn'>Send Meeting Request</button></div></div>"
        }).parent().delegate('button#send', 'click', function() {
            var start = $('.group').find('#start').val();
            var date = $('.group').find('#date').val();
            var duration = $('.group').find('#duration').val();
            var venue = $('.group').find('#venue').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "<?= url('/meeting/scheduleGroup')?>",
                data: {
                    id:<?=$id?>,
                    start: start,
                    date: date,
                    duration: duration,
                    venue: venue
                },
                beforeSend: function() {
                    $('.form-group').removeClass('has-error');
                    $('.help-block').remove();
                    $('.group').find('#status').parent().LoadingOverlay('show');
                },
                success: function(data) {
                    $('.group').find('#status').parent().LoadingOverlay('hide',true);
                    if(data.success == false)
                    {
                        var arr = data.errors;
                        $.each(arr, function(index, value)
                        {
                            if (value.length != 0)
                            {
                                $('input[name='+index+']').parent().addClass('has-error');
                                $('input[name='+index+']').after('<span class="help-block"><strong>'+ value +'</strong></span>');
                            }
                        });
                    }
                    if (data.conflict){
                        $('.group').find('#status').html(data.conflict);
                    }
                    if (data.success){
                        $('.group').find('#status').html(data.success);
                    }
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
    }
    $('.nextweek').on('click', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url: "<?= url('/meeting/gettimetable')?>",
            data: {
                monday: $('.group').find('#monday').val(),
                tuesday: $('.group').find('#tuesday').val(),
                wednesday: $('.group').find('#wednesday').val(),
                thursday: $('.group').find('#thursday').val(),
                friday: $('.group').find('#friday').val(),
                button: 'next',
                id:<?=$id?>,
                type: 'group'
            },
            beforeSend: function () {
                $.LoadingOverlay('show');
            },
            success: function(data) {
                $.LoadingOverlay('hide',true);
                $('.group').html(data);
            },
            error: function (xhr, status) {
                console.log(status);
                console.log(xhr.responseText);
            }
        });
    });
    $('.prevweek').on('click', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url: "<?= url('/meeting/gettimetable')?>",
            data: {
                monday: $('.group').find('#monday').val(),
                tuesday: $('.group').find('#tuesday').val(),
                wednesday: $('.group').find('#wednesday').val(),
                thursday: $('.group').find('#thursday').val(),
                friday: $('.group').find('#friday').val(),
                id:<?=$id?>,
                type: 'group'
            },
            beforeSend: function () {
                $.LoadingOverlay('show');
            },
            success: function(data) {
                $.LoadingOverlay('hide',true);
                $('.group').html(data);
            },
            error: function (xhr, status) {
                console.log(status);
                console.log(xhr.responseText);
            }
        });
    });
</script>