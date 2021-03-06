@extends('layouts.sidemenu')

@section('main')
    <div data-animation="fadeInUp">
        <div class="heading heading-center m-b-40">
            <h2>Dashboard</h2>
            <div class="separator">
                <span>A brief overview of all your activities</span>
            </div>
        </div>
        <div class="col-md-12">
            <h3>Upcoming Meetings</h3>
            <hr>
            @if(count($meetings) > 0)
                <div class="carousel" data-dots="true" data-margin="30" data-items="4">
                    @foreach($meetings as $meeting)
                        <div class="portfolio-item img-zoom pf-illustrations pf-uielements pf-media">
                            <div class="portfolio-item-wrap">
                                <div class="portfolio-image">
                                    <a href="#"><img src="{{asset('public/images/meeting.jpg')}}" alt=""></a>
                                </div>
                                <div class="portfolio-description" style="width: 100%;">
                                    <a>
                                        <h3>{{$meeting->name}}</h3>
                                        <span><label class="label label-info">Date</label> {{date('F d,Y',strtotime($meeting->date))}}</span>
                                        <br>
                                        <span><label class="label label-info">Time</label> {{date('h:iA',strtotime(explode('-',$meeting->time)[0])).'-'.date('h:iA',strtotime(explode('-',$meeting->time)[1]))}}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if(count($meetings) > 6)
                        <div class="portfolio-item img-zoom pf-illustrations pf-uielements pf-media">
                            <div class="portfolio-item-wrap">
                                <div class="portfolio-image">
                                    <a href="#"><img src="{{asset('public/images/meeting.jpg')}}" alt=""></a>
                                </div>
                                <div class="portfolio-description" style="width: 100%;">
                                    <a href="{{url('/meetings')}}" class="btn btn-default">View All</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center">
                    <i class="fa fa-ban fa-5x"></i>
                    <h5>No upcoming meetings to display</h5>
                </div>
            @endif
            <h3>Groups</h3>
            <hr>
            @if(count($groups) > 0)
                <div class="carousel" data-dots="true" data-margin="30" data-items="4">
                    @foreach($groups as $group)
                        <div class="portfolio-item img-zoom pf-illustrations pf-uielements pf-media">
                            <div class="portfolio-item-wrap">
                                <div class="portfolio-image">
                                    <a href="#"><img src="{{asset('public/images/group.jpg')}}" alt=""></a>
                                </div>
                                <div class="portfolio-description" style="width: 100%;">
                                    <a>
                                        <h3>{{$group->groupname}}</h3>
                                        <span><label class="label label-info">Admin</label> {{$group->creator}}</span>
                                        <br>
                                        <span><label class="label label-info">Created On</label> {{$group->created_on}}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if(count($groups) > 6)
                        <div class="portfolio-item img-zoom pf-illustrations pf-uielements pf-media">
                            <div class="portfolio-item-wrap">
                                <div class="portfolio-image">
                                    <a href="#"><img src="{{asset('public/images/group.jpg')}}" alt=""></a>
                                </div>
                                <div class="portfolio-description" style="width: 100%;">
                                    <a href="{{url('/group/all')}}" class="btn btn-default">View All</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center">
                    <i class="fa fa-ban fa-5x"></i>
                    <h5>No groups to display</h5>
                </div>
            @endif
        </div>
    </div>
@endsection
