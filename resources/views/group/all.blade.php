@extends('layouts.sidemenu')

@section('main')
    <div class="row scheduled">
        <div class="col-md-12">
            <h4 style="text-align: center;"><a>My Groups</a></h4>
            <hr>
            @if(session('message'))
                <div class="alert alert-success groupMessage">
                    {{ session()->pull('message') }}
                </div>
            @endif
            @if (count($groups) > 0)
                <ul style="list-style: none; padding-left: 0px;">
                    @foreach($groups as $group)
                        <li style="display: inline-block; width: 49.5%;">
                            <div id="group_<?= $group->id ?>" class="notification-box" style="position: relative;">
                                <button data-toggle="modal" data-target="#dropModal_<?= $group->id?>" class="btn btn-danger drop hover-action">Leave <i class="fa fa-window-close fa-lg" aria-hidden="true"></i></button>
                                <button style="left:0pt;" data-toggle="modal" data-target="#infoModal_<?= $group->id?>" class="btn btn-danger drop hover-action">Group Info <i class="fa fa-info fa-lg" aria-hidden="true"></i></button>
                                Group Name: <?= $group->groupname ?>
                                <br>
                                Admin: <?= $group->creator ?>
                                <br>
                                Created On: <?= $group->created_on ?>
                            </div>
                            <div class="modal fade" id="dropModal_<?= $group->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="text-align: center;">
                                            @if(count($group->members) > 1 && $group->id_creator == Auth::id())
                                                <h2>Select an admin before you leave the group</h2>
                                            @else
                                                <h2>Are you sure you want to leave the group?</h2>
                                            @endif
                                        </div>
                                        <div class="modal-body">
                                            @if($group->id_creator == Auth::id())
                                            <table class="table">
                                                <thead>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Campusid</th>
                                                </thead>
                                                <tbody>
                                                @if(count($group->members) > 1)
                                                    @foreach($group->members as $groupMember)
                                                        @if($groupMember->id != Auth::id())
                                                            <tr>
                                                                <td><h4><?= $groupMember->name?></h4></td>
                                                                <td><h4><?= $groupMember->type?></h4></td>
                                                                <td><h4><?= $groupMember->campusid?></h4></td>
                                                                <?php if ($group->id_creator == Auth::id()): ?>
                                                                    <td><input class="makeAdmin" type="radio" name="makeAdmin" value="<?=$groupMember->id?>"> Make Admin</td>
                                                                <?php endif?>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td><h4>Group has no other members at the moment</h4></td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                            @endif
                                            <div class="row" style="text-align: center;">
                                                <div class="col-md-12">
                                                    <button id="yes_<?= $group->id ?>" class="button_sliding_bg_2 yes">Leave Group</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="infoModal_<?= $group->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="text-align: center;">
                                            <h2>Group Details</h2>
                                            @if($group->id_creator == Auth::id())
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <select class="searchuser" multiple="multiple" style="width: 70%;"></select>
                                                        <input type="button" data-groupid="<?= $group->id ?>" class="addUser button1" value="Add Users">
                                                    </div>
                                                </div>
                                                <div class="alert alert-success requestMessage" style="display:none;">
                                                    <hr>
                                                    <strong>Requests have been sent to all members of the group!</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-success removeMessage" style="display:none;">
                                                <strong>Member Removed Successfully!</strong>
                                            </div>
                                            <table class="table">
                                                <thead>
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th>Campusid</th>
                                                </thead>
                                                <tbody>
                                                @if(count($group->members) > 1)
                                                    @foreach($group->members as $groupMember)
                                                        @if($groupMember->id != Auth::id())
                                                        <tr>
                                                            <td><h4><?= $groupMember->name?></h4></td>
                                                            <td><h4><?= $groupMember->type?></h4></td>
                                                            <td><h4><?= $groupMember->campusid?></h4></td>
                                                            <?php if ($group->id_creator == Auth::id()): ?>
                                                                <td><button id="removeMember_<?= $groupMember->id?>" data-groupid="<?=$group->id?>" class="button1 remove">Remove <i class="fa fa-window-close fa-lg" aria-hidden="true"></i></button></td>
                                                            <?php endif?>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>Group has no other members at the moment</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <h4 style="text-align: center;">You have no groups at the moment</h4>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.yes').on('click', function () {
                var groupid = $(this).attr('id').split('_')[1];
                var adminid = $(this).parents('.modal-content').find('.makeAdmin:checked').val();
                if(adminid == null && $(this).parents('.modal-content').find('.makeAdmin').length){
                    alert('Please select an admin');
                    return;
                }
                if (!$(this).parents('.modal-content').find('.makeAdmin').length){
                    adminid = 'empty';
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "./leaveGroup",
                    data: {
                        groupid: groupid,
                        adminid: adminid
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
            $('.addUser').on('click' , function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var su = $(this).siblings('.searchuser');
                var rq = $(this).parents('.modal-header').find('.requestMessage');
                if (su.val() != '') {
                    $.ajax({
                        method: "POST",
                        url: "./sendGroupRequest",
                        data: {
                            ids: su.val(),
                            groupid: $(this).data('groupid')
                        },
                        success: function(data) {
                            rq.show();
                            window.setTimeout(function () {
                                rq.fadeTo(500, 0).slideUp(500, function () {
                                });
                            }, 3000);
                        },
                        error: function (xhr, status) {
//                    console.log(status);
//                    console.log(xhr.responseText);
                        }
                    });
                }
            });
            $('.searchuser').select2({
                placeholder: 'Select a User',
                ajax: {
                    url: './adduser',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                allowClear: true,
                minimumInputLength: 2
            });

            $('.remove').on('click', function () {
                var userid = $(this).attr('id').split('_')[1];
                var groupid = $(this).data('groupid');
                var element = $(this);
                var rq = $(this).parents('.modal-content').find('.removeMessage');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "./removeMember",
                    data: {
                        userid: userid,
                        groupid: groupid
                    },
                    success: function(data) {
                        rq.show();
                        window.setTimeout(function () {
                            rq.fadeTo(500, 0).slideUp(500, function () {
                                element.closest('tr').remove();
                            });
                        }, 3000);
                    },
                    error: function (xhr, status) {
                        console.log(status);
                        console.log(xhr.responseText);
                    }
                });
            });
        });
        if($('.groupMessage')) {
            $('.groupMessage').show();
            window.setTimeout(function () {
                $(".groupMessage").fadeTo(500, 0).slideUp(500, function () {
                });
            }, 3000);
        }
    </script>
@endsection