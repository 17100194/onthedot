<?php
if (!isset($active)) {
    $active = 'users';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'On the DOT') }}</title>

    <!-- Stylesheets & Fonts -->
    <link rel="shortcut icon" href="{{ asset('public/images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,800,700,600|Montserrat:400,500,600,700|Raleway:100,300,600,700,800" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/plugins.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/responsive.css')}}" rel="stylesheet">

    <script src="{{asset('js/jquery.js')}}"></script>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="side-panel side-panel-static">
<!-- Side Panel -->
<div id="side-panel" class="text-center">
    <div id="close-panel">
        <i class="fa fa-close"></i>
    </div>

    <div class="side-panel-wrap">
        <div class="logo">
            <a href="{{url('/admin/dashboard')}}"><img src="{{asset('public/images/onthedot.png')}}" class="img-responsive"></a>
        </div>
        <hr style="border-width: 5px; border-color: grey;">
        <!--Navigation-->
        <div id="mainMenu" class="menu-onclick menu-vertical">
            <div class="container">
                <nav>
                    <ul>
                        <li class="<?php if ($active == "users"): ?>current<?php endif; ?>"><a href="{{url('/admin/dashboard')}}"><i class="fa fa-user"></i>Users</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <!--end: Navigation-->
    </div>
</div>
<!-- end: Side Panel -->
    @yield('adminbody')

<!-- Go to top button -->
<a id="goToTop"><i class="fa fa-angle-up top-icon"></i><i class="fa fa-angle-up"></i></a>

<!--Plugins-->
<script src="{{asset('js/plugins.js')}}"></script>

<!--Template functions-->
<script src="{{asset('js/functions.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>

</body>
</html>