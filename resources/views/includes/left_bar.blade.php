<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner"></div>
    </div>
</div>


<!-- Begin page -->
<div id="wrapper">

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="">
                {{--<a href="" class="logo"><img src="{{ URL::asset('assets/images/resources/leftBar.jpg')}}" height="45" alt="logo"></a>--}}
                <a href="" class="logo"><h2>-SYSTEM-</h2></a>
            </div>
        </div>
        <br/>
        <div class="sidebar-inner slimscrollleft">
            <div id="sidebar-menu">

                <ul>


                    <li>
                        <a href="{{ url('/') }}" class="waves-effect"><i
                                    class="dripicons-device-desktop"></i><span>{{ __('Dashboard') }} </span></a>
                    </li>

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)
                        <li class="menu-title">{{ __('HIERARCHY') }}</li>
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>{{ __('Hierarchy') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('electionDivision')}}">{{ __('Election Division') }}</a></li>
                                <li><a href="{{route('pollingBooth')}}">{{ __('Polling Booth') }}</a></li>
                                <li><a href="{{route('gramasewaDivision')}}">{{ __('Gramasewa Division') }}</a></li>
                                <li><a href="{{route('village')}}">{{ __('Village') }}</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 3)
                        <li class="menu-title">{{ __('ANALYSIS') }}</li>
                        @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i
                                            class="dripicons-suitcase"></i><span>{{ __('Post') }}
                                        <span
                                                class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{route('createPost')}}">{{ __('Create Post') }}</a></li>
                                    <li><a href="{{route('viewPosts')}}">{{ __('View Posts') }}</a></li>
                                </ul>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 2)
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i
                                            class="dripicons-suitcase"></i><span>{{ __('Category') }}
                                        <span
                                                class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{route('addCategory')}}">{{ __('Add Category') }}</a></li>
                                    <li><a href="{{route('viewCategory')}}">{{ __('View Category') }}</a></li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role ==3 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 4)
                        @if(\Illuminate\Support\Facades\Auth::user()->iduser_role ==3 )
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect">
                                    <i class="dripicons-suitcase"></i>
                                    <span>{{ __('Staff') }}
                                        <span class="pull-right">
                                            <i class="mdi mdi-chevron-right"></i>
                                        </span>
                                    </span>
                                </a>
                                <ul class="list-unstyled">
                                    <li><a href="{{route('assignStaff')}}">{{ __('Assign Staff') }}</a></li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    <li class="menu-title">{{ __('ADMINISTRATION') }}</li>

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 2)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Payments') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('addPayment')}}">{{ __('Add Payment') }}</a></li>
                                <li><a href="{{route('viewOutstandingPayments')}}">{{ __('View Outstanding') }}</a>
                                </li>
                                <li><a href="{{route('viewPayments')}}">{{ __('View Payments') }}</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Office') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('addOffice')}}">{{ __('Add Office') }}</a></li>
                                <li><a href="{{route('viewOffice')}}">{{ __('View Offices') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Task') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('assignTask')}}">{{ __('Assign Task') }}</a></li>
                                <li><a href="{{route('viewTasks')}}">{{ __('View Task') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 3)

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Users') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('addUser')}}">{{ __('Add Users') }}</a></li>
                                <li><a href="{{route('pendingAgents')}}">{{ __('Pending Users') }}</a></li>
                                <li><a href="{{route('viewUser')}}">{{ __('View Users') }}</a></li>
                            </ul>
                        </li>
                    @endif

                </ul>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->