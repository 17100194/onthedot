@extends('layouts.header')

@section('bodycontent')
    <!-- Section -->
    <section class="fullscreen" style="background:#d3d3d3; padding: 0;">
        <div class="container container-fullscreen">
            <div>
                <div class="text-center m-b-30">
                    <a href="{{url('/')}}" class="logo">
                        <img src="{{asset('public/images/onthedot.png')}}" style="width: 350px;" alt="Logo">
                    </a>
                </div>
                <div >
                    <div class="col-md-4 center p-30 background-white b-r-6">
                        <h3>Create New Password</h3>
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> {{ session()->pull('message') }}
                            </div>
                        @endif
                        <form class="form-transparent-grey" role="form" method="POST" action="{{ url('/password/reset') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group{{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
                                <label class="sr-only">Email</label>
                                <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ $email or old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
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
                                <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-default btn-block btn-shadow">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end: Section -->
@endsection
