@extends('layouts.sidemenu')

@section('main')
    <h1>Enroll in a Course</h1>
    <div class="alert alert-success" style="display:none;">
        <strong>Course Enrolled Successfully!</strong> You can now view it in your courses.
    </div>
    <hr>
    <div class="searchbar">
        <div class='form navbar-form navbar-right searchform'>
            <input type="text" class="form-control search_term" placeholder="Search for a course...">
            <input type="submit" class="btn btn-primary searchQuery" value="Search"/>
        </div>
    </div>
    <div id="searchcourse">

    </div>

    <script type="text/javascript">
        $('.searchQuery').on('click', function(){
            var search_term = $('.search_term').val();
            $('#searchcourse').html('<h4>Searching...</h4>');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: "POST",
                url: "./searchcourse",
                data: {
                    search_term: search_term
                },
                success: function(data) {
                    $('#searchcourse').html(data);
                },
                error: function (xhr, status) {
//                    console.log(status);
//                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endsection