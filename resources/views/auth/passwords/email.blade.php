@extends('layouts.header')

<!-- Main Content -->
@section('bodycontent')
    <!-- Section -->
    <section class="fullscreen" style="background:url({{asset('public/images/meeting-background.png')}}); background-size: cover; padding: 0;">
        <div class="container container-fullscreen">
            <div class="text-middle">
                <div class="text-center m-b-30">
                    <a href="{{url('/')}}" class="logo">
                        <img src="{{asset('public/images/onthedot.png')}}" style="width: 350px;" alt="Logo">
                    </a>
                </div>
                <div >
                    <div class="col-md-4 center p-30 background-white b-r-6">
                        <h3>Forgot Password?</h3>
                        @if(session()->has('status'))
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button>
                                <i class="fa fa-check-circle"></i> {{ session()->pull('status') }}
                            </div>
                        @endif
                        <p class="subtitle">Enter your email to recover your password.
                            You will receive an email with instructions.</p>
                        <form class="form-transparent-grey" role="form" method="POST" action="{{ url('/password/email') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
                                <label class="sr-only">Email</label>
                                <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-default btn-block btn-shadow">Send Password Reset Link</button>
                                <a href="{{url('login')}}" class="left">Sign In</a>
                                <p class="right">Don't have an account yet? <a href="{{url('register')}}">Sign Up</a></p>
                            </div>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end: Section -->
@endsection
