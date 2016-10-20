<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet" type="text/css" >
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet" type="text/css" >

    <!-- Scripts -->
    <script>
        function startTime() {
            var today = new Date();
            document.getElementById('time').innerHTML =
                    today;
            var t = setTimeout(startTime, 1000);
        }
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body onload="startTime()">
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                        <?php echo e(config('app.name', 'Laravel')); ?>

                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <?php if(Auth::guest()): ?>
                            <li><a href="<?php echo e(url('/login')); ?>">Login</a></li>
                            <li><a href="<?php echo e(url('/register')); ?>">Register</a></li>
                        <?php else: ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="<?php echo e(url('/logout')); ?>"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="<?php echo e(url('/logout')); ?>" method="POST" style="display: none;">
                                            <?php echo e(csrf_field()); ?>

                                        </form>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <?php echo $__env->yieldContent('content'); ?>
        <div class="container" style="width: 100%; height: auto; background-color: White;">
            <div class="row">
                <div class="col-md-6">
                    <form class="form-horizontal" style="text-align: center;">
                        <h3><a>Contact Us</a></h3>
                        <hr style="width: 90%; border-width: 4px; border-color: #d3d3d3;">
                        <div class="form-group">
                            <label for="id" class="col-md-3 control-label">ID:</label>
                            <div class="col-md-6">
                                <input id="id" name="id" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-md-3 control-label">Message:</label>
                            <div class="col-md-6">
                                <textarea id="message" name="message" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div style="float: right;">
                        <label style="color: #0080ff;">Server Time: </label> <label id="time"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
