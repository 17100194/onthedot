@extends('layouts.sidemenu')

@section('main')
    <div data-animation="fadeInUp">
        <div class="heading heading-center m-b-40">
            <h2>Schedule Meeting</h2>
            <div class="separator">
                <span>Search for a user or group below to schedule a meeting with them</span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-sm-6 center">
                <div class="input-group">
                    <input type="search" name="searchuser" class="form-control" id="searchuser" placeholder="Search for a user or group">
                    <span class="input-group-btn">
                    <button id="search" type="button" class="btn" style="height: 38px;">Search</button>
                </span>
                </div>
            </div>
            <hr>
            <div id="search_result">

            </div>
        </div>
    </div>
    <script>
        $('#search').on('click', function(){
            var search_term = $('#searchuser').val();
            $('#search_result').html('<img style="width:200px;" class="center-block" src="{{asset('public/images/three-dots.svg')}}">');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "./searchuser",
                data: {
                    searchuser: search_term
                },
                beforeSend: function() {
                    $('.input-group').removeClass('has-error');
                    $('.help-block').remove();
                },
                success: function(data) {
                    if(data.success == false)
                    {
                        $('#search_result').empty();
                        var arr = data.errors;
                        $.each(arr, function(index, value)
                        {
                            if (value.length != 0)
                            {
                                $('input[name='+index+']').parent().addClass('has-error');
                                $('input[name='+index+']').parent().after('<span class="help-block"><strong>'+ value +'</strong></span>');
                            }
                        });
                    } else {
                        $('#search_result').html(data);
                    }
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endsection
