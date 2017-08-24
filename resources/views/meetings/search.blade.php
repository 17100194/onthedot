@extends('layouts.app')

@section('content')
    <section id="page-content">
        <div data-animation="fadeInUp">
            <div class="heading heading-center m-b-40">
                <h2>Search Results for: <?=$query?></h2>
                <div class="separator">
                    <span>Displaying all the users and groups found</span>
                </div>
            </div>
            <div>
                <div class="col-md-12">
                    <div id="tabs">
                        <div class="tabs border">
                            <ul class="tabs-navigation">
                                <li class="active"><a href="#students">Students <span class="badge"><?=$users->total()?></span></a></li>
                                <li><a href="#instructors">Instructors <span class="badge"><?=$instructors->total()?></span></a></li>
                                <li><a href="#groups">Groups <span class="badge"><?=$groups->total()?></span></a></li>
                            </ul>
                            <div class="tabs-content">
                                <div class="tab-pane active" id="students">
                                    <?php if (count($users) > 0):?>
                                    <div class="row team-members team-members-left team-members-shadow m-b-40">
                                        <?php foreach ($users as $user):?>
                                        <div class="col-md-6">
                                            <div class="team-member">
                                                <div class="team-image">
                                                    <img src="<?= asset('public/images/'.$user->avatar)?>">
                                                </div>
                                                <div class="team-desc">
                                                    <h3><?=$user->name?></h3>
                                                    <span><?=$user->campusid?></span>
                                                    <hr>
                                                    <div class="align-center">
                                                        <a class="btn btn-rounded" data-target="#studenttimetable" data-id="<?=$user->id?>" data-toggle="modal">
                                                            <span>Set a meeting</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach;?>
                                    </div>
                                    <div class="modal fade" id="studenttimetable" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content student">

                                            </div>
                                        </div>
                                    </div>
                                    <?php echo $users->appends(['searchuser'=>$query])->links();?>
                                    <?php else:?>
                                    <h5>No Students Found</h5>
                                    <?php endif?>
                                </div>
                                <div class="tab-pane" id="instructors">
                                    <?php if (count($instructors) > 0):?>
                                    <div class="row team-members team-members-left team-members-shadow m-b-40">
                                        <?php foreach ($instructors as $instructor):?>
                                        <div class="col-md-6">
                                            <div class="team-member">
                                                <div class="team-image">
                                                    <img src="<?= asset('public/images/'.$instructor->avatar)?>">
                                                </div>
                                                <div class="team-desc">
                                                    <h3><?=$instructor->name?></h3>
                                                    <span><?=$instructor->campusid?></span>
                                                    <hr>
                                                    <div class="align-center">
                                                        <a class="btn btn-rounded" data-target="#instructortimetable" data-id="<?=$instructor->id?>" data-toggle="modal">
                                                            <span>Set a meeting</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach;?>
                                    </div>
                                    <div class="modal fade" id="instructortimetable" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content instructor">

                                            </div>
                                        </div>
                                    </div>
                                    <?php echo $instructors->appends(['searchuser'=>$query])->links();?>
                                    <?php else:?>
                                    <h5>No Instructors Found</h5>
                                    <?php endif?>
                                </div>
                                <div class="tab-pane" id="groups">
                                    <?php if (count($groups) > 0):?>
                                    <div class="row team-members team-members-left team-members-shadow m-b-40">
                                        <?php foreach ($groups as $group):?>
                                        <div class="col-md-6">
                                            <div class="team-member">
                                                <div class="team-image">
                                                    <img src="<?= asset('public/images/'.$group->avatar)?>">
                                                </div>
                                                <div class="team-desc">
                                                    <h3><?=$group->name?></h3>
                                                    <span>Admin: <?=$group->admin?></span>
                                                    <br>
                                                    <?php
                                                    $members = [];
                                                    foreach ($group->userlist as $member){
                                                        $members[] = $member->name.' '.'('.$member->campusid.')';
                                                    }
                                                    ?>
                                                    <span>Members: <?php if (count($members) > 0) {
                                                            echo implode('|', $members);
                                                        } else {
                                                            echo 'None';
                                                        }?></span>
                                                    <hr>
                                                    <div class="align-center">
                                                        <a class="btn btn-rounded" data-target="#grouptimetable" data-id="<?=$group->id?>" data-toggle="modal">
                                                            <span>Set a meeting</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach;?>
                                    </div>
                                    <div class="modal fade" id="grouptimetable" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content group">

                                            </div>
                                        </div>
                                    </div>
                                    <?php echo $groups->appends(['searchuser'=>$query])->links();?>
                                    <?php else:?>
                                    <h5>No Groups Found</h5>
                                    <?php endif?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $('.modal-dialog').parent().on('show.bs.modal', function(e){ $(e.relatedTarget.attributes['data-target'].value).appendTo('body'); });
        var $tabNavigation = $(".tabs-navigation a");
        if ($tabNavigation.length > 0) {
            $tabNavigation.on("click", function (e) {
                $(this).tab("show"), e.preventDefault();
                return false;
            });
        }
        $('#students').on('click','.btn-rounded',function (e) {
            $('.student').html('<h3 class="text-center">Loading...</h3><img style="width:200px;" class="center-block" src="<?= asset('public/images/three-dots.svg')?>">');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "{{url('/meeting/gettimetable')}}",
                data: {
                    id: $(this).data('id'),
                    type: 'student'
                },
                success: function(data) {
                    $('.student').html(data);
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
        $('#instructors').on('click','.btn-rounded',function (e) {
            $('.instructor').html('<h3 class="text-center">Loading...</h3><img style="width:200px;" class="center-block" src="<?= asset('public/images/three-dots.svg')?>">');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "{{url('/meeting/gettimetable')}}",
                data: {
                    id: $(this).data('id'),
                    type: 'instructor'
                },
                success: function(data) {
                    $('.instructor').html(data);
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
        $('#groups').on('click','.btn-rounded',function (e) {
            $('.group').html('<h3 class="text-center">Loading...</h3><img style="width:200px;" class="center-block" src="<?= asset('public/images/three-dots.svg')?>">');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "{{url('/meeting/gettimetable')}}",
                data: {
                    id: $(this).data('id'),
                    type: 'group'
                },
                success: function(data) {
                    $('.group').html(data);
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
        $('#students').on('click', '.pagination li>a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#students').html('<img style="width:200px;" class="center-block" src=<?= asset('public/images/three-dots.svg')?>>');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url
            }).done(function (data) {
                $('#tabs').html(data);
            }).fail(function () {
                alert('No students found.')
            });
        });
        $('#instructors').on('click', '.pagination li>a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#instructors').html('<img style="width:200px;" class="center-block" src=<?= asset('public/images/three-dots.svg')?>>');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url
            }).done(function (data) {
                $('#tabs').html(data);
            }).fail(function () {
                alert('No instructors found.')
            });
        });
        $('#groups').on('click', '.pagination li>a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#groups').html('<img style="width:200px;" class="center-block" src=<?= asset('public/images/three-dots.svg')?>>');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url
            }).done(function (data) {
                $('#tabs').html(data);
            }).fail(function () {
                alert('No groups found.')
            });
        });
    </script>
@endsection
