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

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
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
<div id="wrapper">
    @yield('bodycontent')
</div>

<!-- Go to top button -->
<a id="goToTop"><i class="fa fa-angle-up top-icon"></i><i class="fa fa-angle-up"></i></a>
<button type="button" class="btn btn-dark btn-shadow btn-icon-holder mainContactForm" style="opacity: 1;
    position: fixed;
    right: -63px;
    bottom: 200px;
    cursor: pointer;
    z-index: 999; -moz-transform:rotate(-90deg);
    -ms-transform:rotate(-90deg);
    -o-transform:rotate(-90deg);
    -webkit-transform:rotate(-90deg);">Contact Us<i class="fa fa-caret-up"></i></button>

<!--Plugins-->
<script src="{{asset('js/jquery.js')}}"></script>
<script src="{{asset('js/plugins.js')}}"></script>

<!--Template functions-->
<script src="{{asset('js/functions.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script>
    $('.mainContactForm').popover({
        html: true,
        placement: 'left',
        title: '24/7 Feedback System',
        content: "<div id='contact_status'></div><form id='contact_form'><div class='form-group'><label>Name</label><input class='form-control' id='contact_name' name='contact_name' type='text'></div><div class='form-group'><label>Email</label><input class='form-control' id='contact_email' name='contact_email' type='email'></div><div class='form-group'><label>Message</label><textarea style='height:100px;' class='form-control' id='contact_message' name='contact_message'></textarea></div><div class='form-group'><button id='contact_send' type='button' class='btn btn-default btn-shadow'><i class='fa fa-paper-plane'></i>Send Message</button></div></form>"
    }).parent().delegate('button#contact_send', 'click', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "GET",
            url: "<?= url('/sendcontactform')?>",
            data: $('#contact_form').serialize(),
            beforeSend: function() {
                $('.form-group').removeClass('has-error');
                $('.help-block').remove();
                $('#contact_status').html('<div class="text-center">Processing</div><img src="<?=asset('public/images/preloader.gif')?>" class="center-block">');
            },
            success: function(data) {
                $('#contact_status').html('');
                if(data.success == false)
                {
                    var arr = data.errors;
                    $.each(arr, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            if (index == 'contact_message'){
                                $('textarea[name='+index+']').parent().addClass('has-error');
                                $('textarea[name='+index+']').after('<span class="help-block"><strong>'+ value +'</strong></span>');
                            }
                            $('input[name='+index+']').parent().addClass('has-error');
                            $('input[name='+index+']').after('<span class="help-block"><strong>'+ value +'</strong></span>');
                        }
                    });
                } else {
                    $('#contact_status').html(data);
                }
            },
            error: function (xhr, status) {
                console.log(status);
                console.log(xhr.responseText);
            }
        });
    });
</script>

</body>
</html>