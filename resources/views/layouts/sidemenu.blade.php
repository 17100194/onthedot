@extends('layouts.app')

@section('content')
    <?php
    if (!isset($active)) {
        $active = 'dashboard';
    }
    ?>
    <?php
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
    ?>
    @if($agent->isMobile() || $agent->isTablet())
        <div class="page-menu">
            <div class="container">
                <div class="menu-title">Dashboard Menu <span>Options</span></div>
                <nav>
                    <ul>
                        <li class="<?php if ($active == "dashboard"): ?>active<?php endif; ?>"><a href="<?php echo url('/dashboard') ?>">Dashboard</a></li>
                        <li class="<?php if ($active == "meeting"): ?>active<?php endif; ?>"><a href="<?php echo url('/meeting/schedule') ?>">Schedule Meeting</a></li>
                        <li class="<?php if ($active == "requested"): ?>active<?php endif; ?>"><a href='<?php echo url('/requested') ?>' >Sent Requests</a></li>
                        <li class="<?php if ($active == "requests"): ?>active<?php endif; ?>"><a href="<?php echo url('/meetings/requests') ?>" >Meeting Requests</a></li>
                        <li class="<?php if ($active == "group-requests"): ?>active<?php endif; ?>"><a href="<?php echo url('/group/requests') ?>" >Group Requests</a></li>
                        <li class="<?php if ($active == "view-meeting"): ?>active<?php endif; ?>"><a href="<?php echo url('/meetings') ?>" >My Meetings</a></li>
                        <li class="<?php if ($active == "mygroups"): ?>active<?php endif; ?>"><a href="<?php echo url('/group/all') ?>" >My Groups</a></li>
                        <li class="<?php if ($active == "group"): ?>active<?php endif; ?>"><a href="<?php echo url('/group/make') ?>" >Make a Group</a></li>
                        <li class="<?php if ($active == "timetable"): ?>active<?php endif; ?>"><a href="<?php echo url('/timetable') ?>" >My Timetable</a></li>
                    </ul>
                </nav>

                <div id="menu-responsive-icon">
                    <i class="fa fa-reorder"></i>
                </div>

            </div>
        </div>
        <section id="page-content">
            <div class="container">
                <div class="row">
                    @yield('main')
                </div>
            </div>
        </section>
    @else
        <section id="page-content" class="sidebar-left">
            <div>
                <div class="row">
                    <div class="sidebar sidebar-modern col-md-2" style="padding-right: 0; text-align: center;">
                        <div id="sticky">
                            <ul class="nav nav-pills nav-stacked">
                                <li class="<?php if ($active == "dashboard"): ?>active<?php endif; ?>"><a href="<?php echo url('/dashboard') ?>">Dashboard</a></li>
                                <li class="<?php if ($active == "meeting"): ?>active<?php endif; ?>"><a href="<?php echo url('/meeting/schedule') ?>">Schedule Meeting</a></li>
                                <li class="<?php if ($active == "requested"): ?>active<?php endif; ?>"><a href='<?php echo url('/requested') ?>' >Sent Requests</a></li>
                                <li class="<?php if ($active == "requests"): ?>active<?php endif; ?>"><a href="<?php echo url('/meetings/requests') ?>" >Meeting Requests</a></li>
                                <li class="<?php if ($active == "group-requests"): ?>active<?php endif; ?>"><a href="<?php echo url('/group/requests') ?>" >Group Requests</a></li>
                                <li class="<?php if ($active == "view-meeting"): ?>active<?php endif; ?>"><a href="<?php echo url('/meetings') ?>" >My Meetings</a></li>
                                <li class="<?php if ($active == "mygroups"): ?>active<?php endif; ?>"><a href="<?php echo url('/group/all') ?>" >My Groups</a></li>
                                <li class="<?php if ($active == "group"): ?>active<?php endif; ?>"><a href="<?php echo url('/group/make') ?>" >Make a Group</a></li>
                                <li class="<?php if ($active == "timetable"): ?>active<?php endif; ?>"><a href="<?php echo url('/timetable') ?>" >My Timetable</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="content col-md-10">
                        @yield('main')
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection