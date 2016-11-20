<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome back!</div>

                <div class="panel-body">
                    <form method="get" action="<?php echo e(action('MeetingsController@q')); ?>" class='form navbar-form navbar-right searchform'>
                        <input name="search" class="form-control" placeholder="search for a user">
                        <input type="submit" class="btn btn-primary" value="Search"/>
                    </form>
                    <h4><a>My Courses</a></h4>
                    <?php if(count($courses) > 0): ?>
                        <ul style="list-style: none;">
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <li>
                                    <div style="padding: 15px;">
                                        Course: <?= $course->name ?>
                                        <br>
                                        Timing: <?= $course->timing ?>
                                        <br>
                                        Section: <?= $course->section ?>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>