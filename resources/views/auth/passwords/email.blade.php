@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="container" style="width:90%; background-color: white;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading" style=" background-color: transparent; color:#3b3a36; font-weight:bold; text-align: center;">
                    <h3>Reset Password</h3>
                    <hr style="width:25%; border-width: 3px;">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}

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

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="button1">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection
