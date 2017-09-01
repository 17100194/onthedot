<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Document title -->
    <title>{{ config('app.name', 'On the DOT') }}</title>

    <!-- Stylesheets & Fonts -->
    <link rel="shortcut icon" href="{{ asset('public/images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,800,700,600|Montserrat:400,500,600,700|Raleway:100,300,600,700,800" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/plugins.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/responsive.css')}}" rel="stylesheet">

    <link href="{{asset('css/select2.css')}}" rel="stylesheet">

    <!-- Timetable CSS -->
    <link href="{{asset('css/reset.css')}}" rel="stylesheet">
    <link href="{{asset('css/timetable.css')}}" rel="stylesheet">

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script src="{{asset('js/jquery.sticky-sidebar-scroll.min.js')}}"></script>

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <!-- LOADING FONTS AND ICONS -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:500%2C400%2C700" rel="stylesheet" property="stylesheet" type="text/css" media="all">

    <link rel="stylesheet" type="text/css" href="{{asset('js/plugins/revolution/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('js/plugins/revolution/fonts/font-awesome/css/font-awesome.css')}}">

    <!-- REVOLUTION STYLE SHEETS -->
    <link rel="stylesheet" type="text/css" href="{{asset('js/plugins/revolution/css/settings.css')}}">

    <style type="text/css">.tiny_bullet_slider .tp-bullet:before{content:" ";  position:absolute;  width:100%;  height:25px;  top:-12px;  left:0px;  background:transparent}</style>
    <style type="text/css">.bullet-bar.tp-bullets{}.bullet-bar.tp-bullets:before{content:" ";position:absolute;width:100%;height:100%;background:transparent;padding:10px;margin-left:-10px;margin-top:-10px;box-sizing:content-box}.bullet-bar .tp-bullet{width:60px;height:3px;position:absolute;background:#aaa;  background:rgba(204,204,204,0.5);cursor:pointer;box-sizing:content-box}.bullet-bar .tp-bullet:hover,.bullet-bar .tp-bullet.selected{background:rgba(204,204,204,1)}.bullet-bar .tp-bullet-image{}.bullet-bar .tp-bullet-title{}</style>


    <!-- REVOLUTION JS FILES -->
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/jquery.themepunch.tools.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/jquery.themepunch.revolution.min.js')}}"></script>

    <!-- SLICEY ADD-ON FILES -->
    <script type='text/javascript' src='{{asset('js/plugins/revolution/revolution-addons/slicey/js/revolution.addon.slicey.min.js?ver=1.0.0')}}'></script>

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/extensions/revolution.extension.actions.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/extensions/revolution.extension.carousel.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/extensions/revolution.extension.kenburn.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/extensions/revolution.extension.layeranimation.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/extensions/revolution.extension.migration.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/extensions/revolution.extension.navigation.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/extensions/revolution.extension.parallax.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/extensions/revolution.extension.slideanims.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/revolution/js/extensions/revolution.extension.video.min.js')}}"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-105714173-1', 'auto');
        ga('send', 'pageview');

    </script>
</head>

<body>

<!-- Wrapper -->
<div id="wrapper">

    <!-- Header -->
    <header id="header" class="<?php if (Request::is('/')):?>header-transparent<?php else:?><?php endif?>">
        <div id="header-wrap">
            <div class="container">
                <!--Logo-->
                <div id="logo">
                    <a href="{{url('/')}}" class="logo" data-dark-logo="{{asset('public/images/onthedot.png')}}">
                        <img src="{{asset('public/images/onthedot.png')}}" alt="Logo">
                    </a>
                </div>
                <!--End: Logo-->

                <!--Top Search Form-->
                <div id="top-search">
                    <form method="get" action="{{ action('MeetingsController@q') }}">
                        <input required type="text" name="query" class="form-control" value="" placeholder="Search for a member & press  &quot;Enter&quot;">
                    </form>
                </div>
                <!--end: Top Search Form-->

                <!--Header Extras-->
                <div class="header-extras">
                    <ul>
                        <li class="hidden-sm hidden-xs">
                            @if(Auth::guest())
                            <a href="{{url('register')}}" class="btn btn-outline">Sign Up</a>
                            @endif
                        </li>
                        <li>
                            <!--top search-->
                            <a id="top-search-trigger" href="#" class="toggle-item">
                                <i class="fa fa-search"></i>
                                <i class="fa fa-close"></i>
                            </a>
                            <!--end: top search-->
                        </li>
                    </ul>
                </div>
                <!--end: Header Extras-->

                <!--Navigation Resposnive Trigger-->
                <div id="mainMenu-trigger">
                    <button class="lines-button x"> <span class="lines"></span> </button>
                </div>
                <!--end: Navigation Resposnive Trigger-->

                <!--Navigation-->
                <div id="mainMenu" class="menu-onclick">
                    <div class="container">
                        <nav>
                            <ul>
                                @if (Auth::guest())
                                    <li><a href="{{ url('/login') }}" >Login</a></li>
                                    <li class="visible-sm visible-xs"><a href="{{url('register')}}">Sign Up</a></li>
                                @else
                                    <li class="dropdown">
                                        <a id="notification-dropdown" href="#"><i class="fa fa-bell fa-4x" aria-hidden="true"></i><span id="notificationCounter" style="display: none;"></span></a>
                                        <ul class="dropdown-menu" style="text-align: center; min-width: 400px; right: 4px; left: auto;">
                                            <h6 style="border-bottom: 2px solid grey; text-align: left; margin: 0;">Notifications</h6>
                                            <div id="notification-content">
                                                <div id="notifications">

                                                </div>
                                                <img id="loading" src="{{asset('public/images/preloader.gif')}}" class="center-block" style="display: none;">
                                            </div>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#">{{ Auth::user()->name }} <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ url('/dashboard') }}">
                                                    <i class="fa fa-user" aria-hidden="true"></i> Dashboard
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{url('/settings')}}">
                                                    <i class="fa fa-cog" aria-hidden="true"></i> My Account
                                                </a>
                                            </li>
                                            @if(Auth::user()->isadmin == 1)
                                            <li>
                                                <a href="{{url('/admin/dashboard')}}"><i class="fa fa-lock"></i> Admin Panel</a>
                                            </li>
                                            @endif
                                            <li>
                                                <a href="{{ url('/logout') }}"
                                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                    <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                                                </a>
                                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
                <!--end: Navigation-->
            </div>
        </div>
    </header>
    <!-- end: Header -->
        @yield('content')
    <!-- Footer -->
    <footer id="footer" class="footer-light">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Footer widget area 1 -->
                        <div class="widget clearfix widget-contact-us" style="background-image: url({{asset('public/images/world-map-dark.png')}}); background-position: 50% 20px; background-repeat: no-repeat">
                            <h4>About On the Dot</h4>
                            <p><strong>OntheDot</strong> is an institution based meeting scheduling platform where we've tried to make the process as automated as possible with minimal human intervention.</p>
                            <!-- Social icons -->
                            <div class="social-icons social-icons-border float-left m-t-20">
                                <ul>
                                    <li class="social-facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                </ul>
                            </div>
                            <!-- end: Social icons -->
                        </div>
                        <!-- end: Footer widget area 1 -->
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-content">
            <div class="container">
                <div class="copyright-text text-center">&copy; 2017 OntheDot. All Rights Reserved.
                </div>
            </div>
        </div>
    </footer>
    <!-- end: Footer -->
</div>
<!-- end: Wrapper -->

<!-- Go to top button -->
<a id="goToTop"><i class="fa fa-angle-up top-icon"></i><i class="fa fa-angle-up"></i></a>

<!--Plugins-->
<script src="{{asset('js/plugins.js')}}"></script>
<script src="{{asset('js/jquery.slimscroll.min.js')}}"></script>

<!--Template functions-->
<script src="{{asset('js/functions.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>

@if(!Auth::guest())
<script>
    $(document).ready(function () {
        $('#notification-content').slimScroll({
            height: '350px'
        });
        $('#notification-content').on('scroll', function() {
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                var url = $(this).find('.pagination li>a[rel=next]').attr('href');
                if (url == null){
                    return;
                }
                $('#loading').show();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url : url
                }).done(function (data) {
                    $('#loading').hide();
                    $('#notifications').find('.pagination').remove();
                    $('#notifications').append(data.html);
                    if (data.count > 0){
                        $('#notificationCounter').show();
                        $('#notificationCounter').html(data.count);
                    } else {
                        $('#notificationCounter').hide();
                    }
                }).fail(function () {
                    alert('No new notifications.')
                });
            }
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url: "{{url('/getnotifications')}}",
            beforeSend: function () {
                $('#loading').show();
            },
            success: function(data) {
                $('#loading').hide();
                $('#notifications').html(data.html);
                if (data.count > 0){
                    $('#notificationCounter').show();
                    $('#notificationCounter').html(data.count);
                } else {
                    $('#notificationCounter').hide();
                }
            },
            error: function (xhr, status) {
                console.log(status);
                console.log(xhr.responseText);
            }
        });
        setInterval(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "{{url('/getnotifications')}}",
                success: function(data) {
                    $('#notifications').html(data.html);
                    if (data.count > 0){
                        $('#notificationCounter').show();
                        $('#notificationCounter').html(data.count);
                    } else {
                        $('#notificationCounter').hide();
                    }
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        },20000);
        $('#notification-dropdown').on('click',function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "{{url('/seenotifications')}}",
                success: function(data) {
                    $('#notificationCounter').hide();
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
        setInterval(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "{{url('/checknotifications')}}",
                success: function(data) {
                    $.each(data.notifications,function (index,value) {
                        $('#notifications').prepend(value.html);
                    });
                    if (data.meeting > 0){
                        $('#meeting_requests').fadeIn("slow",function () {
                           $(this).html('New');
                        });
                    } else {
                        $('#meeting_requests').fadeOut("slow");
                    }
                    if (data.group > 0){
                        $('#group_requests').fadeIn("slow",function () {
                            $(this).html('New');
                        });
                    } else {
                        $('#group_requests').fadeOut("slow");
                    }
                    if (data.count > 0){
                        $('#notificationCounter').show();
                        $('#notificationCounter').html(data.count);
                    } else {
                        $('#notificationCounter').hide();
                    }
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        },5000)
    });
</script>
@endif
</body>
</html>
