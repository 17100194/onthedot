@extends('layouts.sidemenu')

@section('main')
    <h4 style="text-align: center;"><a>Create a Group</a></h4>
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
                <div class="error_name" style="display:none; color: darkred;">
                    This field is required*
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="group_name" class="col-md-4 control-label">Add Users</label>
            <div class="col-md-6">
                <select class="searchuser" multiple="multiple" style="width: 100%;">
                </select>
                <div class="error_users" style="display: none; color: darkred;">
                    This field is required*
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-5">
                <input type="button" class="makeGroup button_sliding_bg_2" value="Make Group">
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
                if($('.searchuser').val() != ''){
                    $('.error_users').hide();
                }
                if($('#group_name').val() != ''){
                    $('.error_name').hide();
                }
                if ($('.searchuser').val() != '' && $('#group_name').val() != '') {
                    $.ajax({
                        method: "POST",
                        url: "./makegroup",
                        data: {
                            ids: $('.searchuser').val(),
                            groupname: $('#group_name').val()
                        },
                        success: function(data) {
                            $('.searchuser').empty();
                            $('#group_name').val("");
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
                else{
                    if($('.searchuser').val() == ''){
                        $('.error_users').show();
                    }
                    if($('#group_name').val() == ''){
                        $('.error_name').show();
                    }
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
