<?php
use App\Http\Controllers\MeetingsController;
?>
<div id="tabs">
    <div class="tabs border">
        <ul class="tabs-navigation">
            <li class="active"><a href="#students">Students <span class="badge"><?=$users->total()?></span></a></li>
            <li><a href="#instructors">Instructors <span class="badge"><?=$instructors->total()?></span></a></li>
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
        </div>
    </div>
</div>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url: "<?=url('/meeting/gettimetable')?>",
            data: {
                id: $(this).data('id'),
                type: 'student'
            },
            beforeSend: function () {
                $.LoadingOverlay('show');
            },
            success: function(data) {
                $.LoadingOverlay('hide',true);
                $('.student').html(data);
            },
            error: function (xhr, status) {
                console.log(status);
                console.log(xhr.responseText);
            }
        });
    });
    $('#instructors').on('click','.btn-rounded',function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url: "<?=url('/meeting/gettimetable')?>",
            data: {
                id: $(this).data('id'),
                type: 'instructor'
            },
            beforeSend: function () {
                $.LoadingOverlay('show');
            },
            success: function(data) {
                $.LoadingOverlay('hide',true);
                $('.instructor').html(data);
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : url,
            beforeSend: function () {
                $('#students').LoadingOverlay('show');
            }
        }).done(function (data) {
            $('#tabs').LoadingOverlay('hide',true);
            $('#tabs').html(data);
        }).fail(function () {
            alert('No students found.')
        });
    });
    $('#instructors').on('click', '.pagination li>a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : url,
            beforeSend: function () {
                $('#instructors').LoadingOverlay('show');
            }
        }).done(function (data) {
            $('#tabs').LoadingOverlay('hide',true);
            $('#tabs').html(data);
        }).fail(function () {
            alert('No instructors found.')
        });
    });
</script>