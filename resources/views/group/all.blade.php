@extends('layouts.sidemenu')

@section('main')
    <div class="row scheduled">
        <div class="col-md-12">
            <h4><a>My Groups</a></h4>
            <hr>
            @if (count($groups) > 0)
                <ul style="list-style: none; padding-left: 0px;">
                    @foreach($groups as $group)
                        <li style="display: inline-block; width: 49.5%;">
                            <div id="group_<?= $group->id ?>" class="notification-box" style="position: relative;">
                                <button data-toggle="modal" data-target="#dropModal_<?= $group->id?>" class="btn btn-danger drop hover-action">Leave <i class="fa fa-window-close fa-lg" aria-hidden="true"></i></button>
                                Group Name: <?= $group->groupname ?>
                                <br>
                                By: <?= $group->creator ?>
                                <br>
                                Created On: <?= $group->created_on ?>
                            </div>
                            <div class="modal fade" id="dropModal_<?= $group->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="text-align: center;">
                                            <h2>Are you sure you want to leave this group?</h2>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row" style="text-align: center;">
                                                <div class="col-md-6">
                                                    <button id="yes_<?= $group->id ?>" class="btn btn-success btn-lg yes">Yes</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button id="no_<?= $group->id ?>" class="btn btn-warning btn-lg no">No</button>
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
                <p style="margin-left: 20px;">You have no groups at the moment</p>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.yes').on('click', function () {
                var groupid = $(this).attr('id').split('_')[1];

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "./leaveGroup",
                    data: {
                        groupid: groupid
                    },
                    success: function(data) {
//                    alert(data);
                        location.reload();
                    },
                    error: function (xhr, status) {
                        console.log(status);
                        console.log(xhr.responseText);
                    }
                });
            });
            $('.no').on('click', function () {
                var groupid = $(this).attr('id').split('_')[1];
                jQuery.noConflict();
                $('#dropModal_'+groupid).modal('hide');
            });
        });

    </script>
@endsection