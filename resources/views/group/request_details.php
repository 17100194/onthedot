<div class="modal-header">
    <div class="status"></div>
    <div class="hr-title center">
        <abbr>Group Details</abbr>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-6">
                <h5><span class="label label-info">Admin</span> <?=$group->creator->name?></h5>
            </div>
            <div class="col-sm-6">
                <h5><span class="label label-info">Created On</span> <?=$group->created_on?></h5>
            </div>
        </div>
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="hr-title center">
                <abbr>Group Members</abbr>
            </div>
            <div class="table-responsive">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="row">
        <button class="btn btn-success accept"><i class="fa fa-check"></i> Accept</button>
        <button class="btn btn-danger reject"><i class="fa fa-times"></i> Reject</button>
    </div>
</div>
<script>
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
            data: {idcreator: <?=$group->idcreator?>, groupid: <?=$group->id?>}
        }).done(function (data) {
            $('.table-responsive').html(data);
        }).fail(function () {
            alert('No Users found.')
        });

        $(".accept").on('click',function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./acceptRequest",
                data: {
                    requestid: <?=$requestid?>
                },
                success: function(data) {
                    location.reload();
                },
                error: function (xhr, status) {
                }
            });
        });

        $(".reject").on('click',function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./rejectRequest",
                data: {
                    requestid: <?=$requestid?>
                },
                success: function(data) {
                    location.reload();
                },
                error: function (xhr, status) {
                }
            });
        });
    });
</script>