@extends('layouts.app')

@section('content')
    <style>
        .col-md-12 {
            padding: 0px;
        }
    </style>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
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
                <div class="tab-pane active" id="all" role="tabpanel">
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
                                                                    <h4>Student</h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <h4><?= $user->campusid ?></h4>
                                                                </div>
                                                                <div class="col-md-3">

                                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#UserTimetable">Schedule Meeting</button>
                                                                </div>
                                                            </div>
                                                            <input id="userid" style="display: block;" type="hidden" value="<?= $user->id ?>">




                                                            <div class="modal bs-example-modal-lg" id="UserTimetable" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <p>Select a Time slot</p>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table id="timetable" class="table table-bordered">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th></th>
                                                                                    <th>Monday <?= date("d/m/Y",strtotime('monday')); ?></th>
                                                                                    <th>Tuesday <?= date("d/m/Y",strtotime('tuesday')); ?></th>
                                                                                    <th>Wednesday <?= date("d/m/Y",strtotime('wednesday')); ?></th>
                                                                                    <th>Thursday <?= date("d/m/Y",strtotime('thursday')); ?></th>
                                                                                    <th>Friday <?= date("d/m/Y",strtotime('friday')); ?></th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                @foreach($usercourses as $course)
                                                                                    @if($course->userID == $user->id)
                                                                                        <tr>
                                                                                            <th scope="row"><?= $course->timing ?></th>
                                                                                            @if(strpos($course->days, 'Mon') !== false)
                                                                                                <td><?= $course->courseName ?>
                                                                                                    <br>
                                                                                                    Section: <?= $course->section ?></td>
                                                                                            @else
                                                                                                <td>
                                                                                                    <button class="btn btn-primary select">Select</button>
                                                                                                </td>
                                                                                            @endif
                                                                                            @if(strpos($course->days, 'Tu') !== false)
                                                                                                <td><?= $course->courseName ?>
                                                                                                    <br>
                                                                                                    Section: <?= $course->section ?></td>
                                                                                            @else
                                                                                                <td><button class="btn btn-primary select">Select</button></td>
                                                                                            @endif
                                                                                            @if(strpos($course->days, 'Wed') !== false)
                                                                                                <td><?= $course->courseName ?>
                                                                                                    <br>
                                                                                                    Section: <?= $course->section ?></td>
                                                                                            @else
                                                                                                <td><button class="btn btn-primary select">Select</button></td>
                                                                                            @endif
                                                                                            @if(strpos($course->days, 'Th') !== false)
                                                                                                <td><?= $course->courseName ?>
                                                                                                    <br>
                                                                                                    Section: <?= $course->section ?></td>
                                                                                            @else
                                                                                                <td><button class="btn btn-primary select">Select</button></td>
                                                                                            @endif
                                                                                            @if(strpos($course->days, 'Fri') !== false)
                                                                                                <td><?= $course->courseName ?>
                                                                                                    <br>
                                                                                                    Section: <?= $course->section ?></td>
                                                                                            @else
                                                                                                <td><button class="btn btn-primary select">Select</button></td>
                                                                                            @endif
                                                                                        </tr>
                                                                                    @endif
                                                                                @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                            <div class="alert alert-success" style="display:none;">
                                                                                <strong>Meeting Request Send!</strong> It will appear as soon as the participant agrees.
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
                                                                    <h4>Student</h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <h4><?= $user->campusid ?></h4>
                                                                </div>
                                                                <div class="col-md-3">

                                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#UserTimetable">Schedule Meeting</button>
                                                                </div>
                                                            </div>
                                                            <input id="userid" style="display: block;" type="hidden" value="<?= $user->id ?>">

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
        $('.select').on('click', function(){
            var cell = $(this).closest('td');
            var cellIndex = cell[0].cellIndex
            var row = cell.closest('tr');
            var rowIndex = row[0].rowIndex;
            var time = document.getElementById('timetable').rows[rowIndex].cells[0].innerText;
            var date = document.getElementById('timetable').rows[0].cells[cellIndex].innerText.split(' ')[1];
            var day = document.getElementById('timetable').rows[0].cells[cellIndex].innerText.split(' ')[0];

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
//            console.log(time, date, day, $('#userid').val())
            $.ajax({
                method: "POST",
                url: "./schedule",
                data: {
                    Time: time,
                    Date: date,
                    Day: day,
                    User: $('#userid').val()
                },
                success: function(data) {
                        $('.alert').show();
                        window.setTimeout(function () {
                            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                                $(this).remove();
                                window.location.href = "{{URL::to('/home')}}";
                            });
                        }, 4000);
                },
                error: function (xhr, status) {
//                    console.log(status);
//                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endsection
