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
            <div class="separator">
                <span>A virtual display of all your week to week activities</span>
            </div>
        </div>
            <div class="col-md-12">
                <div id="timetable">

                </div>
            </div>
    </div>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "./showtimetable",
                data: {},
                beforeSend: function () {
                  $.LoadingOverlay('show');
                },
                success: function(data) {
                    $.LoadingOverlay('hide',true);
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