@extends('layouts.sidemenu')

@section('main')
    <h4 style="text-align: center;"><a>Enroll in a Course</a></h4>
    <div class="alert alert-success" style="display:none;">
        <strong>Course Enrolled Successfully!</strong> You can now view it in your courses.
    </div>
    <hr>
    <div>
        <p>Search for a course that you're taking this semester. You may search for the course code (example, CS100)
        or the course name as well (example, Introduction to Computing).</p>
    </div>
    <div class="searchbar">
        <div class='form navbar-form navbar-right searchform'>
            <input type="text" class="form-control search_term" placeholder="Search for a course...">
            <input type="submit" class="btn btn-primary searchQuery" value="Search"/>
            <hr style="border-width:3px;">
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