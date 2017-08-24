@extends('layouts.sidemenu')

@section('main')
    <div data-animation="fadeInUp">
        <div class="alert alert-success alert-dismissable" style="display:none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
            <strong>Course Enrolled Successfully!</strong> You can now view it in your courses.
        </div>
        <div class="heading heading-center m-b-40">
            <h2>Enroll in a Course</h2>
            <div class="separator">
                <span>Search for a course that you're taking this semester. You may search for the course code (example, CS100) or the course name as well (example, Introduction to Computing).</span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-5 center">
                <div class="input-group">
                    <input id="searchterm" type="text" name="searchterm" class="form-control" placeholder="Search for a course..." required>
                    <span class="input-group-btn">
                    <button id="search-button" class="btn btn-default" type="button" style="height: 38px;">Search</button>
                </span>
                </div>
            </div>
            <hr>
            <div id="searchcourse">

            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#search-button').on('click', function(){
            var search_term = $('#searchterm').val();
            $('#searchcourse').html('<img style="width:200px;" class="center-block" src="{{asset('public/images/three-dots.svg')}}">');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "./searchcourse",
                data: {
                    searchterm: search_term
                },
                beforeSend: function() {
                    $('.input-group').removeClass('has-error');
                    $('.help-block').remove();
                },
                success: function(data) {
                    if(data.success == false)
                    {
                        $('#searchcourse').empty();
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
                        $('#searchcourse').html(data);
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