@extends('layouts.header')

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
                <div class="row">
                    <div class="col-md-4 center p-30 background-white b-r-6">
                        <h3>Register New Account</h3>
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> {{ session()->pull('message') }}
                            </div>
                        @endif
                        <form class="form-transparent-grey" method="POST" action="{{ url('/register') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('name') ? ' has-error has-feedback' : '' }}">
                                <label class="sr-only">Full Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus placeholder="Full Name" class="form-control">
                                @if ($errors->has('name'))
                                    <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
                                <label class="sr-only">Email</label>
                                <input id="email" name="email" type="email" value="{{old('email')}}" class="form-control" placeholder="Email Address" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
                                <label class="sr-only">Password</label>
                                <input id="password" type="password" placeholder="Password" class="form-control" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
                                <label class="sr-only">Confirm Password</label>
                                <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default btn-block btn-shadow" type="submit">Register New Account</button>
                                <p class="small">Already a member? <a href="{{url('login')}}">Sign In</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end: Section -->
@endsection
