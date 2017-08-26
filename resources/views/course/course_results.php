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
    <h4 class="text-center text-danger">No courses found regarding your query. Search again.</h4>
<?php endif?>

<script type="text/javascript">
    $('.pagination li>a').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#searchcourse').html('<img style="width:200px;" class="center-block" src=<?= asset('public/images/three-dots.svg')?>>');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : url
        }).done(function (data) {
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
                $('.alert').hide();
            },
            success: function(data) {
                $('.alert').show();
            },
            error: function (xhr, status) {
                console.log(status);
                console.log(xhr.responseText);
            }
        });
    });
</script>