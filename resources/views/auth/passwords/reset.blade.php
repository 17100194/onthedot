@extends('layouts.app')

@section('content')
<div class="container" style="width:90%; background-color: white;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading" style=" background-color: transparent; color:#3b3a36; font-weight:bold; text-align: center;">
                    <h3>Reset Password</h3>
                    <hr style="width:25%; border-width: 3px;">
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('campusid') ? ' has-error' : '' }}">
                            <label for="campusid" class="col-md-4 control-label">Campus ID</label>

                            <div class="col-md-6">
                                <input id="campusid" type="text" class="form-control" name="campusid" placeholder="e.g 2017-10-0194" value="{{ old('campusid') }}" required>

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

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection
