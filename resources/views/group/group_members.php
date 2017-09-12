<?php
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Auth;
?>
<table class="table">
    <thead class="background-colored text-light">
    <tr>
        <th>Name</th>
        <th>Campus ID</th>
        <th>Type</th>
        <?php if($idcreator == Auth::id()):?>
            <th>Remove</th>
            <th>Make Admin</th>
        <?php endif?>
    </tr>
    </thead>
    <tbody>
    <?php if (count($groupmembers) == 0):?>
        <tr>
            <td colspan="5" class="text-center"><h5>Group has no members at the moment</h5></td>
        </tr>
    <?php endif?>
    <?php foreach ($groupmembers as $groupMember){?>
        <?php if ($groupMember->id != Auth::id()):?>
            <tr>
                <td><?= $groupMember->name?></td>
                <td><?= $groupMember->campusid?></td>
                <td><?= $groupMember->type?></td>
                <?php if ($idcreator == Auth::id()): ?>
                    <td><button class="btn btn-danger remove btn-xs" value="<?=$groupMember->id?>"><i class="fa fa-times"></i> Remove</button></td>
                    <td><label><input type="radio" class="option-input radio" name="admin" required value="<?=$groupMember->id?>"></label></td>
                <?php endif?>
            </tr>
        <?php endif?>
    <?php }?>
    </tbody>
</table>
<?php echo $groupmembers->render(); ?>
<script>
    $('.remove').on('click', function () {
        var userid = $(this).val();
        if (confirm('Are you sure you want to remove this user?')){
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
                    groupid: <?=$groupid?>
                },
                beforeSend: function () {
                    $('#groupDetails').find('.modal-content').LoadingOverlay('show');
                },
                success: function(data) {
                    showMembers();
                    $('#groupDetails').find('.modal-content').LoadingOverlay('hide',true);
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        }
    });
    function showMembers() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url : './showGroupMembers',
            data: {idcreator: <?=$idcreator?>, groupid: <?=$groupid?>}
        }).done(function (data) {
            $('.table-responsive').html(data);
        }).fail(function () {
            alert('No Users found.')
        });
    }
    $('.pagination li>a').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : url,
            data: {idcreator: <?=$idcreator?>, groupid: <?=$groupid?>},
            beforeSend: function () {
                $('#groupDetails').find('.modal-content').LoadingOverlay('show');
            }
        }).done(function (data) {
            $('#groupDetails').find('.modal-content').LoadingOverlay('hide',true);
            $('.table-responsive').html(data);
        }).fail(function () {
            alert('No Users found.')
        });
    });
</script>
