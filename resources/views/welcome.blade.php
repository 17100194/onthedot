@extends('layouts.app')

@section('content')
    <div class="container-separator" style="width:90%; margin: 0px auto; background-color:#e9ece5;">
        <div class="row">
            <div style="background: url(<?= asset('public/images/cover2.jpg') ?>); height: 450px; background-size: 100% 100%; display: block; text-align:center;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 100px;">
                            <h2 class="animated fadeInLeftBig deepshadow" style="color:#fff;">Scheduling meetings made 2x faster.</h2>
                            <h4 class="animated fadeInLeftBig deepshadow" style="color:#fff; animation-delay: 1s;">Meetings now scheduled in minutes rather than days</h4>
                            <div class="container animated fadeIn" style="margin-top:2%;">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="row">
                                        <form role="form" id="form-buscar" method="get" action="{{ action('MeetingsController@q') }}">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input id="1" class="form-control" type="text" name="query" placeholder="Search for a User or Group.." required/>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" type="submit">
                                                            <i class="fa fa-search fa-lg" aria-hidden="true"></i> Search
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <section class="ss-style-doublediagonal">

            </section>
        </div>
    </div>
@endsection
