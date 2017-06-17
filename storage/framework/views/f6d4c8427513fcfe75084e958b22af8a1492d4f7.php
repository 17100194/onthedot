<!-- Main Content -->
<?php $__env->startSection('bodycontent'); ?>
<div class="container">
    <div class="row center-block">
        <a href="<?php echo e(url('/')); ?>">
            <img src="<?= asset('public/images/onthedot.png') ?>" class="img-responsive" style="display: block; margin: auto;">
        </a>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel-heading" style=" background-color: white; color:#3b3a36; font-weight:bold; text-align: center;">
                <h2>Reset Password</h2>
                <hr style="width:90%; border-width: 3px;">
                <?php if(session('status')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>
                <p style="color: #636b6f">Enter your email to recover your password.
                    You will receive an email with instructions. If you are experimenting problems recovering your password contact us or <a href="mailto:onthedotpk@gmail.com" class="btn-link">send us an email.</a></p>
            </div>
            <div class="panel-body" style="background: white">
                <form class="form-horizontal" role="form" method="POST" action="<?php echo e(url('/password/email')); ?>">
                    <?php echo e(csrf_field()); ?>


                    <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                        <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" required>

                            <?php if($errors->has('email')): ?>
                                <span class="help-block">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="button1" style="width: 100%">
                                Send Password Reset Link
                            </button>
                        </div>
                        <div class="col-md-8 col-md-offset-4">
                            <a class="btn btn-link" href="<?php echo e(url('/login')); ?>">
                                Sign in
                            </a>
                            <a class="btn btn-link" href="<?php echo e(url('/register')); ?>">
                                Not a member? Sign up
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>