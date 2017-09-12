<?php
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Auth;
?>

<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="hr-title center">
                <abbr>Course Details</abbr>
            </div>
            <div class="text-left">
                <form>
                    <input type="hidden" id="courseid" name="courseid" value="<?= $course->courseid?>">
                <div class="form-group">
                    <label>Course Code</label>
                    <input type="text" id="code" name="code" class="form-control" <?php if ($course->instructorid != Auth::id()):?>readonly<?php endif?> value="<?= $course->coursecode?>">
                </div>
                <div class="form-group">
                    <label>Course Name</label>
                    <input type="text" id="name" name="name" class="form-control" <?php if ($course->instructorid != Auth::id()):?>readonly<?php endif?> value="<?= $course->name?>">
                </div>
                <div class="form-group">
                    <label>Section</label>
                    <input type="text" id="section" name="section" class="form-control" placeholder="Numeric value, 1 if only section" <?php if ($course->instructorid != Auth::id()):?>readonly<?php endif?> value="<?= $course->section?>">
                </div>
                <?php
                $start = date('h:iA', strtotime(explode('-', $course->timing)[0]));
                $end = date('h:iA', strtotime(explode('-', $course->timing)[1]));
                ?>
                <div class="form-group">
                    <label>Course Timing</label>
                    <input type="text" id="time" name="time" class="form-control" <?php if ($course->instructorid != Auth::id()):?>readonly<?php endif?> value="<?= $start .'-'.$end?>">
                </div>
                    <div class="form-group">
                        <label>Course Venue</label>
                        <input type="text" id="venue" name="venue" class="form-control" <?php if ($course->instructorid != Auth::id()):?>readonly<?php endif?> value="<?= $course->venue?>">
                    </div>
                <?php if ($course->instructorid != Auth::id()):?>
                <div class="form-group">
                    <label>Course Instructor</label>
                    <input type="text" id="instructor" name="instructor" class="form-control" readonly value="<?= $course->instructor?>">
                </div>
                <?php endif?>
                <div class="form-group">
                    <label>Course Days</label>
                    <input type="text" id="days" name="days" class="form-control" <?php if ($course->instructorid != Auth::id()):?>readonly<?php endif?> value="<?= $course->days?>">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if ($course->instructorid == Auth::id()):?>
<div class="modal-footer">
    <div class="row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-outline drop">Drop Course</button>
            <button type="button" class="btn btn-outline save">Save Changes</button>
        </div>
    </div>
</div>
    <script>
        $('.drop').on('click', function () {
            var courseid = <?= $course->courseid?>;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./dropcourse",
                data: {
                    courseid: courseid
                },
                beforeSend: function () {
                    $('#courseDetails').find('.modal-content').LoadingOverlay('show');
                },
                success: function(data) {
                    $('#courseDetails').find('.modal-content').LoadingOverlay('hide',true);
                    location.reload();
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
        $('.save').on('click', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "./update",
                data: $('form').serialize(),
                beforeSend: function() {
                    $('.form-group').removeClass('has-error');
                    $('.help-block').remove();
                    $('.alert').remove();
                    $('#courseDetails').find('.modal-content').LoadingOverlay('show');
                },
                success: function(data) {
                    $('#courseDetails').find('.modal-content').LoadingOverlay('hide',true);
                    if(data.success == false)
                    {
                        var arr = data.errors;
                        $.each(arr, function(index, value)
                        {
                            if (value.length != 0)
                            {
                                $('input[name='+index+']').parent().addClass('has-error');
                                $('input[name='+index+']').after('<span class="help-block"><strong>'+ value +'</strong></span>');
                            }
                        });
                    } else {
                        $('.text-left').prepend('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>'+data.success+'</div>');
                    }
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
<?php endif?>
