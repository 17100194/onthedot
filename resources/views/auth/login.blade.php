@extends('layouts.header')

@section('bodycontent')
    <!-- Section -->
    <section class="fullscreen" style="background:#d3d3d3; padding: 0;">
        <div class="container container-fullscreen">
            <div class="text-middle">
                <div class="text-center m-b-30">
                    <a href="{{url('/')}}" class="logo">
                        <img src="{{asset('public/images/onthedot.png')}}" style="width: 350px;" alt="Logo">
                    </a>
                </div>
                <div >
                    <div class="col-md-4 center p-30 background-white b-r-6">
                        <h3>Login to your account</h3>
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> {{ session()->pull('message') }}
                            </div>
                        @endif
                        <form class="form-transparent-grey" role="form" method="POST" action="{{ url('/login') }}">
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
                                <label class="sr-only">Email</label>
                                <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group m-b-5 {{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
                                <label class="sr-only">Password</label>
                                <input id="password" name="password" type="password" class="form-control" placeholder="Password" required>
                                @if ($errors->has('password'))
                                    <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group form-inline text-left">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"><small> Remember me</small>
                                    </label>
                                </div>
                                <a href="{{ url('/password/reset') }}" class="float-right"><small>Lost your Password?</small></a>
                            </div>
                            <div class="text-left form-group">
                                <button type="submit" class="btn btn-block btn-shadow">Login</button>
                            </div>
                        </form>
                        <p class="small">Don't have an account yet? <a href="{{url('register')}}">Register Now</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end: Section -->
@endsection
