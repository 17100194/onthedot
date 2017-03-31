@extends('layouts.sidemenu')

@section('main')
    <div class="row courses">
        <div class="col-md-12">
            <h4 style="text-align: center;"><a>My Courses</a></h4>
            <hr>
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session()->pull('message') }}
                </div>
            @endif
            @if (count($courses) > 0)
                <ul style="list-style: none; padding-left: 0px;">
                    @foreach($courses as $course)
                        <li style="display: inline-block; width: 45%;">
                            <div id="course_<?= $course->courseid ?>" class="notification-box" style="position: relative;">
                                <button data-toggle="modal" data-target="#dropModal_<?= $course->courseid ?>" class="hover-action btn btn-danger drop">Drop <i class="fa fa-window-close fa-lg" aria-hidden="true"></i></button>
                                <?= $course->coursecode ?> - <?= $course->section ?>
                                <br>
                                (<?= $course->name ?>)
                                <hr>
                                Instructor: <?= $course->instructor->name ?> (<?= $course->instructor->campusid ?>)
                                <br>
                                <?php
                                $strt = date('h:iA', strtotime(explode('-', $course->timing)[0]));
                                $endt = date('h:iA', strtotime(explode('-', $course->timing)[1]));
                                ?>
                                Timing: <?= $strt .' - '.$endt ?>
                                <br>
                                Days: <?= $course->days ?>
                            </div>
                            <div class="modal fade" id="dropModal_<?= $course->courseid ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="text-align: center;">
                                            <h2>Are you sure you want to drop this course?</h2>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row" style="text-align: center;">
                                                <div class="col-md-6">
                                                    <button id="yes_<?= $course->courseid ?>" class="button_sliding_bg_2 yes">Yes</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button id="no_<?= $course->courseid ?>" class="button_sliding_bg_2 no">No</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="margin-left: 20px;">You have no enrolled courses at the moment</p>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.notification-box').hover(function () {
                $(this).find('.drop').fadeIn();
            }, function () {
                $(this).find('.drop').fadeOut();
            });
            $('.yes').on('click', function () {
                var courseid = $(this).attr('id').split('_')[1];
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "./dropcourse",
                    data: {
                        courseid: courseid
                    },
                    success: function(data) {
                        location.reload();
                    },
                    error: function (xhr, status) {
//                    console.log(status);
//                    console.log(xhr.responseText);
                    }
                });
            });
            $('.no').on('click', function () {
                var courseid = $(this).attr('id').split('_')[1];
                jQuery.noConflict();
                $('#dropModal_'+courseid).modal('hide');
            });
            if($('.alert')) {
                $('.alert').show();
                window.setTimeout(function () {
                    $(".alert").fadeTo(500, 0).slideUp(500, function () {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    </script>
@endsection