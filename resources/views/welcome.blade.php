@extends('layouts.app')

@section('content')
    <div class="container" style="width:90%; margin: 0px auto; background-color:#e9ece5;">
        <div class="row">
            <div style="overflow-x: hidden; background: url(<?= asset('public/images/cover.png') ?>); height: 90vh; background-size: cover;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center" style="color: white; margin-top: 150px">
                            <h2>On the DOT</h2>
                            <hr style="width:25%;">
                            <h4 style="color:#fff;">Scheduling meetings made 2x faster.</h4>
                            <h4 style="color:#fff;">Meetings now scheduled in minutes rather than days</h4>
                            <a href="<?php echo url('home')?>" class="button_sliding_bg">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
