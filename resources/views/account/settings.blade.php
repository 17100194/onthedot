@extends('layouts.app')

@section('content')
    <section id="page-title" class="page-title-left" data-parallax-image="{{asset('/public/images/pagetitle.jpeg')}}">
        <div class="container">
            <div class="page-title">
                <h1>Account Settings</h1>
            </div>
        </div>
    </section>
    <section id="page-content">
        <div class="container">
            <div class="row">
                <h2>Account Details</h2>
                <hr>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" readonly value="{{Auth::user()->name}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" class="form-control" readonly value="{{Auth::user()->email}}">
                    </div>
                </div>
                @if(Auth::user()->type == 'student')
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Campus ID</label>
                            <input type="text" id="campusid" name="campusid" class="form-control" readonly value="{{Auth::user()->campusid}}">
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <h2>Change Password</h2>
                <button class="btn btn-shadow" data-target="#changePassword" data-toggle="modal"><i class="fa fa-lock"></i>Change</button>
            </div>
            <div class="modal fade" id="changePassword" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
                            <div class="status"></div>
                        </div>
                        <div class="modal-body">
                            <div class="hr-title center">
                                <abbr>Change Password</abbr>
                            </div>
                            <div class="form-group">
                                <label>Old Password</label>
                                <input id="old_password" name="old_password" class="form-control" type="password" >
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input id="password" name="password" class="form-control" type="password" >
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input id="password-confirm" name="password-confirm" class="form-control" type="password" >
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn save">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $('.save').on('click',function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "{{url('/updatePassword')}}",
                data: {
                    old_password: $('#old_password').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password-confirm').val()
                },
                beforeSend: function () {
                    $('.form-group').removeClass('has-error');
                    $('.help-block').remove();
                    $.LoadingOverlay('show');
                },
                success: function(data) {
                    $.LoadingOverlay('hide',true);
                    if(data.success == false) {
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
                        $('#changePassword').find('.status').html(data.success);
                    }
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endsection