@extends('layouts.header')

@section('bodycontent')
<div class="container">
    <div class="row center-block">
        <a href="{{ url('/') }}">
            <img src="<?= asset('public/images/onthedot.png') ?>" class="img-responsive" style="display: block; margin: auto;">
        </a>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel-heading" style=" background-color: white; color:#3b3a36; font-weight:bold; text-align: center;">
                <h2>Sign In</h2>
                <hr style="width:90%; border-width: 3px;">
                @if(session()->has('message'))
                    <div class="alert alert-danger">
                        {{ session()->pull('message') }}
                    </div>
                @endif
            </div>
            <div class="panel-body" style="background: white;">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="button1" style="width: 100%;">
                                    Sign In
                                </button>
                            </div>
                            <div class="col-md-8 col-md-offset-4">
                                <a class="btn btn-link" href="{{ url('/register') }}">
                                    Not a member? Sign up
                                </a>
                                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection
