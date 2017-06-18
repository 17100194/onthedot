<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'On the DOT') }}</title>

    <!-- Styles -->
    <link rel="shortcut icon" href="{{ asset('public/images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('public/css/style.css') }}" rel="stylesheet" type="text/css" >
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/normalize.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/demo.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/icons.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/component.css')}}" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600" rel="stylesheet">
</head>
<body>
<!-- Scripts -->
<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script src="{{asset('public/js/modernizr.custom.js')}}"></script>
<script src="{{asset('public/js/modernizr.js')}}"></script>
<script src="{{asset('public/js/main.js')}}"></script>
<script
        src="{{ asset('public/js/app.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('public/js/scripts.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script>

<script>
    window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
    ]); ?>
</script>

<!-- Scripts End -->
<div id="app">
    <nav class="navbar navbar-default" role="navigation" style="background-color:#fff; border: 1px solid #b3c2bf;  width: 90%; margin: 0px auto;">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" style="background-color: #3b3a36; color:#b3c2bf;">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar top-bar"></span>
                    <span class="icon-bar middle-bar"></span>
                    <span class="icon-bar bottom-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}" style="width: 177px; padding-top: 0px;">
                    <img src="<?= asset('public/images/onthedot.png') ?>" class="img-responsive">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}" >Login</a></li>
                        <li><a href="{{ url('/register') }}" >Sign Up</a></li>
                    @else
                        <li class="dropdown">
                            <a class="dropdown-toggle notification-button" data-toggle="dropdown" style="position: relative;" role="button" aria-expanded="false">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                @if (count($notifications) > 0)
                                    <?php $num = 0;?>
                                    @foreach($notifications as $notification)
                                        @if($notification->seen == 'no')
                                            <?php $num = $num + 1?>
                                            @endif
                                        @endforeach
                                    @if($num != 0)
                                    <div class="notification"><?= $num?></div>
                                        @endif
                                @endif
                            </a>
                            <ul id="notifications" class="dropdown-menu" role="menu" style="min-width: 340px; text-align: center; color: #666;">
                                <div style="text-align: center; border-bottom: 1px solid #dddddd; padding: 8px;">Notifications</div>
                                <div id="notificationsBody" class="style-1">
                                    @if (count($requests) > 0)
                                        <ul class="notificationbox" style="list-style: none;">
                                            @foreach($requests as $request)
                                                <li><?= $request?></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <ul>
                                            <h4 style="color: #666;">No notifications at the moment</h4>
                                        </ul>
                                    @endif
                                </div>
                                <a href="#" class="btn-link"><div id="notificationFooter">See All</div></a>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/dashboard') }}">
                                        <i class="fa fa-user" aria-hidden="true"></i> Dashboard
                                    </a>
                                    <a href="#">
                                        <i class="fa fa-cog" aria-hidden="true"></i> My Account
                                    </a>
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
                <form role="form" id="form-buscar" class="navbar-form <?php if (parse_url($_SERVER['REQUEST_URI'])['path'] == "/"):?>hidden<?php endif; ?>" style="outline: none; border: none" method="get" action="{{ action('MeetingsController@q') }}">
                    <div class="form-group">
                        <div class="input-group">
                            <input id="1" class="form-control" type="text" name="query" placeholder="Search for a User or Group.." required/>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search fa-lg" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </nav>
    @yield('content')
</div>
<script>
    $('.notification-button').click(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url: "http://onthedot.herokuapp.com/seeNotifications",
            success: function(data) {
                $('.notification').hide();
            },
            error: function (xhr, status) {
            }
        });
    });
</script>
</body>
</html>
