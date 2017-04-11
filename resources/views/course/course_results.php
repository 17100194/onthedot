<?php use App\Http\Controllers\CourseController; ?>

<div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td>Course Name</td>
            <td>Course Code</td>
            <td>Instructor</td>
            <td>Section</td>
            <td>Time</td>
            <td>Days</td>
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
                    <td>
                        <button data-courseid="<?= $course->courseid ?>" class="<?php if($course->enrolled): ?>btn btn-success disabled<?php else: ?>btn enroll btn-success<?php endif; ?>"><?php if($course->enrolled): ?> Enrolled <?php else: ?> Enroll <?php endif; ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $('.enroll').on('click', function(){
        var courseid = $(this).attr('data-courseid');
        console.log(courseid);
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
            success: function(data) {
                $('.alert').show();
                window.setTimeout(function () {
                    $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    });
                }, 3000);
            },
            error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
            }
        });
    });
</script>