@extends('layouts.app')

@section('content')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if (count($users) === 0)

                    @elseif (count($users) >= 1)
                        <ul style="list-style: none;">
                        @foreach($users as $user)

                            <li>
                                <div style="padding: 15px;">
                                    <?= $user->name ?>
                                    <br>
                                        <?= $user->campusid ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#UserTimetable">Schedule Meeting</button>

                                        <div class="modal fade bs-example-modal-lg" id="UserTimetable" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <p>Select a Time slot</p>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Monday</th>
                                                                <th>Tuesday</th>
                                                                <th>Wednesday</th>
                                                                <th>Thursday</th>
                                                                <th>Friday</th>
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
                                                                            <td><button class="btn btn-primary">Select</button></td>
                                                                        @endif
                                                                        @if(strpos($course->days, 'Tu') !== false)
                                                                            <td><?= $course->courseName ?>
                                                                                <br>
                                                                                Section: <?= $course->section ?></td>
                                                                        @else
                                                                            <td><button class="btn btn-primary">Select</button></td>
                                                                        @endif
                                                                        @if(strpos($course->days, 'Wed') !== false)
                                                                            <td><?= $course->courseName ?>
                                                                                <br>
                                                                                Section: <?= $course->section ?></td>
                                                                        @else
                                                                            <td><button class="btn btn-primary">Select</button></td>
                                                                        @endif
                                                                        @if(strpos($course->days, 'Th') !== false)
                                                                            <td><?= $course->courseName ?>
                                                                                <br>
                                                                                Section: <?= $course->section ?></td>
                                                                        @else
                                                                            <td><button class="btn btn-primary">Select</button></td>
                                                                        @endif
                                                                        @if(strpos($course->days, 'Fri') !== false)
                                                                            <td><?= $course->courseName ?>
                                                                                <br>
                                                                                Section: <?= $course->section ?></td>
                                                                        @else
                                                                            <td><button class="btn btn-primary">Select</button></td>
                                                                        @endif
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </li>
                        @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
