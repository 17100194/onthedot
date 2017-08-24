@extends('layouts.app')

@section('content')
    <section id="page-title" class="page-title-center" data-parallax-image="{{asset('public/images/pagetitle.jpeg')}}">
        <div class="container">
            <div class="page-title">
                <h1>Account Settings</h1>
            </div>
            <div class="breadcrumb">
                <ul>
                    <li><a href="{{url('/')}}">Home</a>
                    </li>
                    <li class="active"><a href="{{url('/settings')}}">Account Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <section id="page-content">
        <div class="container">
            <h2>Account Details</h2>
            <hr>
            <form class="form-transparent-grey">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection