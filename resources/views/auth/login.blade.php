@extends('layouts.app')

@section('content')
<div class="container" style="width:90%; background-color: white;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading" style=" background-color: transparent; color:#3b3a36; font-weight:bold; text-align: center;">
                    <h3>Sign In</h3>
                    <hr style="width:25%; border-width: 3px;">
                    @if(session()->has('message'))
                        <div class="alert alert-danger">
                            {{ session()->pull('message') }}
                        </div>
                    @endif
                </div>
            <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('campusid') ? ' has-error' : '' }}">
                            <label for="campusid" class="col-md-4 control-label">Campus ID</label>

                            <div class="col-md-6">
                                <input id="campusid" type="text" class="form-control" name="campusid" value="{{ old('campusid') }}" required autofocus>

                                @if ($errors->has('campusid'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('campusid') }}</strong>
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
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="button1">
                                    Sign In
                                </button>
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
