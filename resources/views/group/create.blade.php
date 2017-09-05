@extends('layouts.sidemenu')

@section('main')
    <div data-animation="fadeInUp">
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <i class="fa fa-check-circle"></i> <strong>{{ session()->pull('message-bold') }}</strong> {{ session()->pull('message') }}
            </div>
        @endif
        <div class="heading heading-center m-b-40">
            <h2>Make a Group</h2>
            <div class="separator">
                <span>Create a group to schedule a meeting with multiple people at once</span>
            </div>
        </div>
            <div class="col-md-12">
                <form class="form-transparent-grey col-md-5 center" role="form" method="POST" action="{{ url('/makegroup') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('group_name') ? ' has-error has-feedback' : '' }}">
                        <label class="sr-only">Group Name</label>
                        <input id="group_name" type="text" placeholder="Group Name" class="form-control" name="group_name" required>
                        @if ($errors->has('group_name'))
                            <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                            <span class="help-block">
                        <strong>{{ $errors->first('group_name') }}</strong>
                    </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('members') ? ' has-error has-feedback' : '' }}">
                        <label class="sr-only">Add Users</label>
                        <select id="members" name="members[]" class="searchuser" multiple="multiple" required style="width: 100%"></select>
                        @if ($errors->has('members'))
                            <span class="fa fa-close form-control-feedback" aria-hidden="true"></span>
                            <span class="help-block">
                        <strong>{{ $errors->first('members') }}</strong>
                    </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-shadow btn-block" value="Make Group">
                    </div>
                </form>
            </div>
    </div>
    <script type="text/javascript">
        $('.searchuser').select2({
            placeholder: 'Add a user. You can also add multiple users',
            ajax: {
                url: './adduser',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term,
                        groupid: <?=$group->id?>
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            allowClear: true,
            minimumInputLength: 2
        });
    </script>
@endsection
