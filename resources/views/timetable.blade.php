@extends('layouts.sidemenu')

@section('main')
    <div data-animation="fadeInUp">
        @if(session('message'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <i class="fa fa-check-circle"></i> {{ session()->pull('message') }}
            </div>
        @endif
        <div class="heading heading-center m-b-40">
            <h2>My Timetable</h2>
            <span class="lead text-shadow-dark">A virtual display of all your current activities</span>
        </div>
            <div class="col-md-12">
                <div id="timetable">

                </div>
            </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#timetable').html('<h3 class="text-center">Loading...</h3><img style="width:200px;" class="center-block" src="{{asset('public/images/three-dots.svg')}}">');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "./showtimetable",
                data: {},
                success: function(data) {
                    $('#timetable').html(data);
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        })
    </script>
@endsection