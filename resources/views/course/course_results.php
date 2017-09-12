<?php use App\Http\Controllers\CourseController; ?>

<?php if(count($courses) > 0):?>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="background-colored text-light">
        <tr>
            <td>Course Name</td>
            <td>Course Code</td>
            <td>Instructor</td>
            <td>Section</td>
            <td>Time</td>
            <td>Days</td>
            <td>Venue</td>
            <td></td>
        </tr>
        </thead>
        <tbody>
            <?php foreach($courses as $course): ?>
                <tr>
                    <td><?= $course->name ?></td>
                    <td><?= $course->coursecode ?></td>
                    <td><?= $course->username ?></td>
                    <td><?= $course->section ?></td>
                    <?php
                        $strt = date('h:iA', strtotime(explode('-', $course->timing)[0]));
                        $endt = date('h:iA', strtotime(explode('-', $course->timing)[1]));

                    ?>
                    <td><?= $strt .' - '.$endt ?></td>
                    <td><?= $course->days ?></td>
                    <td><?= $course->venue ?></td>
                    <td>
                        <button data-courseid="<?= $course->courseid ?>" class="<?php if($course->enrolled): ?>btn disabled<?php else: ?>btn enroll btn-outline<?php endif; ?>"><?php if($course->enrolled): ?> Enrolled <?php else: ?> Enroll <?php endif; ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $courses->render(); ?>
<?php else:?>
    <div class="text-center">
        <i class="fa fa-ban fa-5x"></i>
        <h5>No results to display</h5>
    </div>
<?php endif?>

<script type="text/javascript">
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
            beforeSend: function () {
              $('#searchcourse').LoadingOverlay('show');
            }
        }).done(function (data) {
            $('#searchcourse').LoadingOverlay('hide',true);
            $('#searchcourse').html(data);
        }).fail(function () {
            alert('No Courses found.')
        });
    });
    $('.enroll').on('click', function(){
        var courseid = $(this).attr('data-courseid');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url: "./enrollcourse",
            data: {
                course_id: courseid
            },
            beforeSend:function () {
                $('#searchcourse').LoadingOverlay('show');
            },
            success: function(data) {
                $('#searchcourse').LoadingOverlay('hide',true);
                $('#status').html(data);
                $('.enroll').addClass('disabled');
                $('.enroll').html('Enrolled');
            },
            error: function (xhr, status) {
                console.log(status);
                console.log(xhr.responseText);
            }
        });
    });
</script>