@extends('layouts.sidemenu')

@section('main')
    <h1>Create a Group</h1>
    <hr>
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/makegroup') }}">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="group_name" class="col-md-4 control-label">Group Name</label>

            <div class="col-md-6">
                <input id="group_name" type="text" class="form-control" name="group_name" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label for="group_name" class="col-md-4 control-label">Add Users</label>
            <div class="col-md-6">
                <select class="searchuser" multiple="multiple">
                </select>
                <input type="button" class="makeGroup btn btn-primary" value="Make Group">
            </div>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.searchuser').select2({
                placeholder: 'Select a User',
                ajax: {
                    url: './adduser',
                    type: 'get',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        });

        $('.makeGroup').click(function() {
            if ($('.searchuser').val() != '' && $('#group_name').val() != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "./makegroup",
                    data: {
                        ids: $('.searchuser').val(),
                        groupname: $('#group_name').val()
                    },
                    success: function(data) {
                    },
                    error: function (xhr, status) {
//                    console.log(status);
//                    console.log(xhr.responseText);
                    }
                });
            }
        })
    </script>
@endsection
