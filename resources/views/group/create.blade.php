@extends('layouts.sidemenu')

@section('main')
    <h4><a>Create a Group</a></h4>
    <hr>
    <div class="alert alert-success" style="display:none;">
        <strong>Group Successfully Made!</strong> Requests have been sent to all members of the group.
    </div>
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
            $('.makeGroup').click(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                console.log($('.searchuser').val());
                if ($('.searchuser').val() != '' && $('#group_name').val() != '') {

                    $.ajax({
                        method: "POST",
                        url: "./makegroup",
                        data: {
                            ids: $('.searchuser').val(),
                            groupname: $('#group_name').val()
                        },
                        success: function(data) {
                            $('.alert').show();
                            window.setTimeout(function () {
                                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                                });
                            }, 3000);
                        },
                        error: function (xhr, status) {
//                    console.log(status);
//                    console.log(xhr.responseText);
                        }
                    });
                }
            });

            $('.searchuser').select2({
                placeholder: 'Select a User',
                ajax: {
                    url: './adduser',
                    dataType: 'json',
                    delay: 250,
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
        });
    </script>
@endsection
