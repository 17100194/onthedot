@extends('layouts.app')

@section('content')
    <style>
        .col-md-12 {
            padding: 0px;
        }
    </style>
    <div class="container">
        <div class="row">
            <ul class="nav search-tabs nav-tabs" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" data-toggle="tab" href="#all" role="tab">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#students" role="tab">Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#instructors" role="tab">Instructors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#groups" role="tab">Groups</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade in active" id="all" role="tabpanel">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    @if (count($users) === 0)
                                    @elseif (count($users) >= 1)
                                        <ul style="list-style: none;">
                                            @foreach($users as $user)
                                                @if($user->id != Auth::id())
                                                    <li>
                                                        <div style="padding: 15px;">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <h4><?= $user->name ?></h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <h4><?= $user->type ?></h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <h4><?= $user->campusid ?></h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <button type="button" class="btn btn-primary schedule" data-toggle="modal" data-target="#UserTimetable_<?= $user->id ?>">Schedule Meeting</button>
                                                                </div>
                                                            </div>
                                                            <input id="userid" style="display: block;" type="hidden" value="<?= $user->id ?>">
                                                            <div class="modal fade bs-example-modal-lg" id="UserTimetable_<?= $user->id ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <p>Select a Time slot</p>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="courses">
                                                                                <?php foreach($allCourses as $course): ?>
                                                                                <?php
                                                                                $left = 0;
                                                                                ?>
                                                                                <?php if(!$course || $course->userid != $user->id): ?>
                                                                                <?php continue; ?>
                                                                                <?php else: ?>
                                                                                <?php foreach($course->days as $day): ?>
                                                                                <?php
                                                                                $left = 90;
                                                                                switch ($day) {
                                                                                    case "Monday":
//
                                                                                        break;
                                                                                    case "Tuesday":
                                                                                        $left += 155;
                                                                                        break;
                                                                                    case "Wednesday":
                                                                                        $left += 155*2;
                                                                                        break;
                                                                                    case "Thursday":
                                                                                        $left += 155*3;
                                                                                        break;
                                                                                    case "Friday":
                                                                                        $left += 155*4;
                                                                                        break;
                                                                                }
                                                                                ?>
                                                                                <div class="ttElement" style="padding: 5px; width: 155px; text-align: center; height: <?=$course->height?>px; top: <?= 78+$course->startingHeight?>px; left:<?=$left?>px;">
                                                                                    <label style="color: white;"><?=$course->name?></label>
                                                                                    <br>
                                                                                    <label style="color: white;"><?=$course->timing?></label>
                                                                                </div>
                                                                                <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                <?php endforeach; ?>
                                                                            </div>
                                                                            <table id="timetable" class="table table-bordered timetable">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th></th>
                                                                                    <th style="width: 156px;">Monday <?= date("d/m/Y",strtotime('monday')); ?></th>
                                                                                    <th style="width: 156px;">Tuesday <?= date("d/m/Y",strtotime('tuesday')); ?></th>
                                                                                    <th style="width: 156px;">Wednesday <?= date("d/m/Y",strtotime('wednesday')); ?></th>
                                                                                    <th style="width: 156px;">Thursday <?= date("d/m/Y",strtotime('thursday')); ?></th>
                                                                                    <th style="width: 156px;">Friday <?= date("d/m/Y",strtotime('friday')); ?></th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <th>8:00am</th>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                    </tr>
                                                                                <tr>
                                                                                    <th>9:00AM</th>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>10:00AM</th>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>11:00AM</th>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>12:00PM</th>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>1:00PM</th>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>2:00PM</th>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>3:00PM</th>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>4:00PM</th>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>5:00PM</th>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <hr>
                                                                            <div class="slot_details_<?= $user->id ?>" style="display:none;">
                                                                                <h4>Enter Meeting Details</h4>
                                                                                <form role="form" class="form-horizontal">
                                                                                    <div class="form-group">
                                                                                        <label for="purpose" class="col-md-4 control-label">Purpose of Meeting</label>
                                                                                        <div class="col-md-6">
                                                                                            <input id="purpose" type="text" class="form-control" name="purpose" required autofocus>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="timing" class="col-md-4 control-label">Timing</label>

                                                                                        <div class="col-md-8">
                                                                                            <div class="col-md-4">
                                                                                                <label for="start_time">Start Time</label>
                                                                                                <div class="input-group">
                                                                                                    <span id="hr_<?= $user->id ?>" class="input-group-addon hr"></span>
                                                                                                    <input type="number" min="0" max="59" id="minute_<?= $user->id ?>" class="form-control minute" aria-describedby="hr"><span class="input-group-addon ampm" id="ampm_<?= $user->id ?>"></span>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label for="end_time">Duration</label>
                                                                                                <input id="duration_<?= $user->id ?>" type="number" min="0" class="form-control" placeholder="Minutes">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="day" class="col-md-4 control-label">Date</label>
                                                                                        <label id="day_<?= $user->id ?>" class="day col-md-3 control-label"></label>
                                                                                    </div>
                                                                                    <hr>
                                                                                    <div class="form-group" style="text-align: center;">
                                                                                            <a id="select" class="btn btn-primary">Send Meeting Request</a>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                            <div class="alert alert-success" style="display:none;">
                                                                                <strong>Meeting Request Send!</strong> It will appear as soon as the participant agrees.
                                                                            </div>
                                                                            <div class="alert alert-warning" style="display:none;">
                                                                                <strong>Conflict Detected!</strong> Meeting cannot be requested at the given time.
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
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="students" role="tabpanel">

                </div>
                <div class="tab-pane" id="instructors" role="tabpanel">

                </div>
                <div class="tab-pane" id="groups" role="tabpanel">

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.search-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        })
    </script>

    <script type="text/javascript">
        var id;
        $('.schedule').click(function () {
           id = $(this).data('target').split('_')[1];
            console.log(id);
        });
        $('#select').on('click', function(){
            var start_time = $('#hr_'+id).text()+$('#minute_'+id).val()+$('#ampm_'+id).text();
            var hrs = start_time.split(":")[0];
            var mins = start_time.split(":")[1].substr(1,2);
            var amPM = start_time.split(":")[1].substr(3,4);
            var duration = $('#duration_'+id).val();
            var day = $('#day_'+id).text().split(' ')[0];
            var date = $('#day_'+id).text().split(' ')[1];
            if(parseInt(mins) >= 60){
                alert('Invalid Start Time');
                return;
            }
            else{
                mins = (parseInt(mins) + parseInt(duration)).toString();
                if(parseInt(mins) >= 60) {
                    mins = (parseInt(mins) - 60).toString();
                    hrs = (parseInt(hrs) + 1).toString();
                    if(parseInt(hrs) >= 12){
                        if(parseInt(hrs) == 12){
                            hrs = hrs;
                            if(amPM == 'AM'){
                                amPM = 'PM';
                            } else {
                                amPM = 'AM';
                            }
                        } else {
                            hrs = (parseInt(hrs) - 12).toString();
                        }
                    }
                    if (mins.length === 1) {
                        mins = '0'+mins;
                    }
                }
            }
            var end_time = hrs +":"+mins+amPM;
            var time = start_time + '-' + end_time;
            time = time.replace(" ", "");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./schedule",
                data: {
                    Time: time,
                    Date: date,
                    Day: day,
                    User: id
                },
                success: function(data) {
                        if(data == 'error'){
                            $('.alert-warning').show();
                            window.setTimeout(function () {
                                $(".alert-warning").fadeTo(500, 0).slideUp(500, function () {
                                    {{--window.location.href = "{{URL::to('/home')}}";--}}
                                });
                            }, 3000);

                        } else {
                            $('.alert-success').show();
                            window.setTimeout(function () {
                                $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                                    window.location.href = "{{URL::to('/home')}}";
                                });
                            }, 3000);
                        }

                },
                error: function (xhr, status) {
                }
            });
        });
        $('.timetable td:not(:first)').hover(function () {
           $(this).css('background-color', 'green')
            $(this).click(function () {
                $('.slot_details_'+id).show()
                var time = $(this).closest('tr').children('th').text();
                $('.hr').html(time.split(':')[0]+" : ");
                $('.ampm').html(time.slice(-2));
                var cell = $(this).closest('td');
                var cellIndex = cell[0].cellIndex
                $('.day').html(document.getElementById('timetable').rows[0].cells[cellIndex].innerText);
            });
        }, function () {
            $(this).css('background-color', '')
        });
    </script>
@endsection
