@extends('layouts.app')

@section('content')
    <?php
        if (!isset($active)) {
            $active = 'dashboard';
        }
    ?>
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
                            <li class="<?php if ($active == "courses"): ?>active<?php endif; ?>"><a href="<?php echo url('/course/all') ?>" >My Courses</a></li>
                            <li class="<?php if ($active == "view-meeting"): ?>active<?php endif; ?>"><a href="<?php echo url('/meetings') ?>" >My Meetings</a></li>
                            <li class="<?php if ($active == "mygroups"): ?>active<?php endif; ?>"><a href="<?php echo url('/group/all') ?>" >My Groups</a></li>
                            <li class="<?php if ($active == "group"): ?>active<?php endif; ?>"><a href="<?php echo url('/group/make') ?>" >Make a Group</a></li>
                            <?php if(Auth::user()->type != 'teacher'): ?>
                            <li class="<?php if ($active == "addcourse"): ?>active<?php endif; ?>"><a href="<?php echo url('/course/enroll') ?>" >Enroll in a Course</a></li>
                            <?php else: ?>
                            <li class="<?php if ($active == "addcourse"): ?>active<?php endif; ?>"><a href="<?php echo url('/course/make') ?>" >Add a Course</a></li>
                            <?php endif; ?>
                            <li class="<?php if ($active == "timetable"): ?>active<?php endif; ?>"><a href="<?php echo url('/timetable') ?>" >My Timetable</a></li>
                        </ul>
                    </div>
                    <?php
                    use Jenssegers\Agent\Agent;
                    $agent = new Agent();
                    ?>
                    @if($agent->isMobile())
                    <div class="page-menu">
                        <div class="container">
                            <div class="menu-title">Page Menu <span>Options</span></div>
                            <nav>
                                <ul>
                                    <li class="active"><a href="page-menu.html">Rounded</a> </li>
                                    <li><a href="page-menu-outline.html">Outline</a> </li>
                                    <li><a href="page-menu-lines.html">Lines</a> </li>
                                    <li><a href="page-menu-classic.html">Classic</a> </li>
                                    <li><a href="page-menu-line-bottom.html">Line Bottom</a> </li>
                                    <li><a href="page-menu-light.html">Light</a> </li>
                                    <li><a href="page-menu-dark.html">Dark</a> </li>
                                    <li><a href="page-menu-creative.html">Creative</a> </li>
                                    <li><a href="page-menu-colored-background.html">Colored Background</a></li>
                                </ul>
                            </nav>

                            <div id="menu-responsive-icon">
                                <i class="fa fa-reorder"></i>
                            </div>

                        </div>
                    </div>
                    @endif
                </div>
                <div class="content col-md-10">
                    @yield('main')
                </div>
            </div>
        </div>
    </section>
@endsection