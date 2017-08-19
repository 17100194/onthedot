<div class="row">
    <div class="col-xs-12"></div>
    <div class="col-xs-12 text-center">
        <span class="text-shadow-dark text-orange" style="font-size: 2rem;"><?=date('F d,Y', strtotime($monday)).' - '.date('F d,Y', strtotime($friday))?></span> <button class="prevweek btn"><i class="fa fa-arrow-left fa-lg"></i></button><button class="nextweek btn"><i class="fa fa-arrow-right fa-lg"></i></button>
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
                <input type="hidden" value="<?=$monday?>" class="monday">
                <div class="top-info monday"><span>Monday<br>(<?=$monday?>)</span></div>
                <ul>
                    <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
                            <?php
                            switch ($day) {
                                case "Monday":?>
                                    <?php if($course->type == 'meeting'){?>
                                        <?php if($course->name == date('Y-m-d',strtotime($monday))){?>
                                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-3">
                                                <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                            </li>
                                        <?php }?>
                                    <?php }else{?>
                                        <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-2">
                                            <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="<?=$course->name?>"></a>
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
                <input type="hidden" value="<?=$tuesday?>" class="tuesday">
                <div class="top-info"><span>Tuesday<br>(<?=$tuesday?>)</span></div>
                <ul>
                    <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
                            <?php
                            switch ($day) {
                                case "Monday":
                                    break;
                                case "Tuesday":?>
                                    <?php if($course->type == 'meeting'){?>
                                        <?php if($course->name == date('Y-m-d',strtotime($tuesday))){?>
                                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-3">
                                                <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                            </li>
                                        <?php }?>
                                    <?php }else{?>
                                        <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-2">
                                            <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="<?=$course->name?>"></a>
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
                <input type="hidden" value="<?=$wednesday?>" class="wednesday">
                <div class="top-info"><span>Wednesday<br>(<?=$wednesday?>)</span></div>
                <ul>
                    <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
                            <?php
                            switch ($day) {
                                case "Monday":
                                    break;
                                case "Tuesday":
                                    break;
                                case "Wednesday":?>
                                    <?php if($course->type == 'meeting'){?>
                                        <?php if($course->name == date('Y-m-d',strtotime($wednesday))){?>
                                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-3">
                                                <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                            </li>
                                        <?php }?>
                                    <?php }else{?>
                                        <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-2">
                                            <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="<?=$course->name?>"></a>
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
                <input type="hidden" value="<?=$thursday?>" class="thursday">
                <div class="top-info"><span>Thursday<br>(<?=$thursday?>)</span></div>
                <ul>
                    <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
                            <?php
                            switch ($day) {
                                case "Monday":
                                    break;
                                case "Tuesday":
                                    break;
                                case "Wednesday":
                                    break;
                                case "Thursday":?>
                                    <?php if($course->type == 'meeting'){?>
                                        <?php if($course->name == date('Y-m-d',strtotime($thursday))){?>
                                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-3">
                                                <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                            </li>
                                        <?php }?>
                                    <?php }else{?>
                                        <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-2">
                                            <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="<?=$course->name?>"></a>
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
                <input type="hidden" value="<?=$friday?>" class="friday">
                <div class="top-info"><span>Friday<br>(<?=$friday?>)</span></div>
                <ul>
                    <?php foreach($allCourses as $course): ?>
                        <?php if(!$course): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php foreach($course->days as $day): ?>
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
                                    <?php if($course->type == 'meeting'){?>
                                        <?php if($course->name == date('Y-m-d',strtotime($friday))){?>
                                            <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-3">
                                                <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="Meeting"></a>
                                            </li>
                                        <?php }?>
                                    <?php }else{?>
                                        <li class="single-event" data-info="<?= $course->content?>" data-start="<?= date("H:i",strtotime(explode('-',$course->timing)[0]))?>" data-end="<?= date("H:i",strtotime(explode('-',$course->timing)[1]))?>" data-event="event-2">
                                            <a href="#0" data-toggle="tooltip" data-placement="top" title data-original-title="<?=$course->name?>"></a>
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

    <div class="event-modal">
        <header class="header">
            <div class="content">
                <span class="event-date"></span>
                <h3 class="event-name"></h3>
            </div>

            <div class="header-bg"></div>
        </header>

        <div class="body">
            <div class="event-info"></div>
            <div class="body-bg"></div>
        </div>

        <a href="#0" class="close">Close</a>
    </div>

    <div class="cover-layer"></div>
</div>
<script src="<?= asset('js/modernizr.js')?>"></script>
<script src="<?= asset('js/main.js')?>"></script>
<script>
    var $tooltip = $('[data-toggle="tooltip"]');
    if ($tooltip.length > 0) {
        $('[data-toggle="tooltip"]').tooltip();
    }
    $('.nextweek').on('click', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url: "./showtimetable",
            data: {
                monday: $('.monday').val(),
                tuesday: $('.tuesday').val(),
                wednesday: $('.wednesday').val(),
                thursday: $('.thursday').val(),
                friday: $('.friday').val(),
                button: 'next'
            },
            beforeSend: function () {
                $('#timetable').html('<h3 class="text-center">Loading...</h3><img style="width:200px;" class="center-block" src="<?= asset('public/images/three-dots.svg')?>">');
            },
            success: function(data) {
                $('#timetable').html(data);
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
            url: "./showtimetable",
            data: {
                monday: $('.monday').val(),
                tuesday: $('.tuesday').val(),
                wednesday: $('.wednesday').val(),
                thursday: $('.thursday').val(),
                friday: $('.friday').val()
            },
            beforeSend: function () {
                $('#timetable').html('<h3 class="text-center">Loading...</h3><img style="width:200px;" class="center-block" src="<?= asset('public/images/three-dots.svg')?>">');
            },
            success: function(data) {
                $('#timetable').html(data);
            },
            error: function (xhr, status) {
                console.log(status);
                console.log(xhr.responseText);
            }
        });
    });
</script>