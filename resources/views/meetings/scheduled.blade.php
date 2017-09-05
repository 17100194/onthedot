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
            <h2>My Scheduled Meetings</h2>
            <div class="separator">
                <span>Details of all your scheduled meetings can be found here</span>
            </div>
        </div>
            <div class="col-md-12">
                @if (count($meetings) > 0)
                    <div class="row col-no-margin equalize" data-equalize-item=".text-box">
                        <?php $colors = array("#506681","#41566f","#32475f"); $key = 0;?>
                        @foreach($meetings as $id=>$meeting)
                            <?php $users = array();?>
                            @foreach($meeting as $value)
                                <?php $users[] = $value->name;?>
                            @endforeach
                            @if($key == 2)
                                <?php $key = 0;?>
                                <div class="space"></div>
                            @endif
                            <div class="col-md-4" style="background-color: {{$colors[$key]}}">
                                <div class="text-box hover-effect" data-target="#meetingDetails" data-id="{{$id}}" data-toggle="modal">
                                    <a>
                                        <i class="fa fa-handshake-o"></i>
                                        <h3>With: <?php if (count($users) > 1):?>Group<?php else:?>{{implode(',',$users)}}<?php endif;?></h3>
                                        <p>Click on the box to view meeting details</p>
                                    </a>
                                </div>
                            </div>
                            <?php $key = $key + 1;?>
                        @endforeach
                    </div>
                    <?= $meetings->setPath(url('/meetings'))->render() ?>
                    <div class="modal fade" id="meetingDetails" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">

                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <i class="fa fa-ban fa-5x"></i>
                        <h5>No results to display</h5>
                        <a href="{{url('meeting/schedule')}}" class="btn">Schedule a meeting</a>
                    </div>
                @endif
            </div>
    </div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.text-box').on('click', function () {
            $('.modal-content').html('<h3 class="text-center">Loading...</h3><img style="width:200px;" class="center-block" src="{{asset('public/images/three-dots.svg')}}">');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "GET",
                url: "./meetingdetails",
                data: {
                    meetingid: $(this).data('id')
                },
                success: function(data) {
                    $('.modal-content').html(data);
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection