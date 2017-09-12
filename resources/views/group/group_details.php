<?php
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Auth;
?>

<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
    <div class="status"></div>
</div>
<div class="modal-body">
    <div class="hr-title center">
        <abbr>Group Details</abbr>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-6">
                <h5><span class="label label-info">Admin</span> <?=$group->creator?></h5>
            </div>
            <div class="col-sm-6">
                <h5><span class="label label-info">Created On</span> <?=$group->created_on?></h5>
            </div>
        </div>
    </div>
    <div class="space"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="hr-title center">
                <abbr>Group Members</abbr>
            </div>
            <div class="table-responsive">
            </div>
        </div>
    </div>
    <?php if($group->id_creator == Auth::id()):?>
        <div class="space"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="hr-title center">
                <abbr>Add Members</abbr>
            </div>
            <div class="input-group">
                <select class="searchuser" multiple="multiple" required></select>
                <span class="input-group-btn">
                    <button type="button" class="btn adduser" style="height: 36px;">Add</button>
                </span>
            </div>
        </div>
    </div>
    <?php endif?>
</div>
<div class="modal-footer">
    <?php if($group->id_creator == Auth::id()):?>
        <p class="text-left text-warning"><strong>Note!</strong> Please select an admin to replace you before you leave the group. If group has no members then your group will automatically be deleted on leaving.</p>
    <?php endif?>
    <button type="button" class="btn btn-outline timetable" data-target="#grouptimetable" data-id="<?=$group->id?>" data-toggle="modal" data-dismiss="modal">Set a Meeting</button>
    <button type="button" class="btn btn-outline leavegroup">Leave Group</button>
</div>
<div class="modal fade" id="grouptimetable" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content group">

        </div>
    </div>
</div>
<script>
    $('body').on('hidden.bs.modal', function (e) {
        if($('.modal').hasClass('in')) {
            $('body').addClass('modal-open');
        }
    });
    $('.timetable').on('click',function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url: "<?= url('/meeting/gettimetable')?>",
            data: {
                id: $(this).data('id'),
                type: 'group'
            },
            beforeSend: function () {
                $('.group').html('');
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
    $('.leavegroup').on('click', function () {
        var adminid = $('input[name=admin]:checked').val();
        if(adminid == null && $('input[name=admin]').length){
            adminid = 'none';
        }
        if (!$('input[name=admin]').length){
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
                groupid: <?=$group->id?>,
                adminid: adminid
            },
            beforeSend: function () {
                $('#groupDetails').find('.modal-content').LoadingOverlay('show');
            },
            success: function(data) {
                $('#groupDetails').find('.modal-content').LoadingOverlay('hide',true);
                if (data == 'success'){
                    location.reload();
                } else {
                    $('.status').html(data);
                }
            },
            error: function (xhr, status) {
                console.log(status);
                console.log(xhr.responseText);
            }
        });
    });
    $(document).ready(function () {
        $('.table-responsive').html("<img src='<?= asset('public/images/preloader.gif')?>' class='center-block'>");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url : './showGroupMembers',
            data: {idcreator: <?=$group->id_creator?>, groupid: <?=$group->id?>}
        }).done(function (data) {
            $('.table-responsive').html(data);
        }).fail(function () {
            alert('No Users found.')
        });

        $('.searchuser').select2({
            placeholder: 'Add a user. You can also add multiple users',
            ajax: {
                url: './adduser',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term,
                        groupid: <?=$group->id?>
                    };
                },
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

        $('.adduser').on('click' , function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./sendGroupRequest",
                data: {
                    ids: $('.searchuser').select2('val'),
                    groupid: <?=$group->id?>
                },
                beforeSend: function () {
                    $('#groupDetails').find('.modal-content').LoadingOverlay('show');
                },
                success: function(data) {
                    $('#groupDetails').find('.modal-content').LoadingOverlay('hide',true);
                    if (data == 'error'){
                        $('.status').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button> <i class="fa fa-times-circle"></i> You must select at least one user</div>');
                    } else {
                        $('.status').html(data);
                    }
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
