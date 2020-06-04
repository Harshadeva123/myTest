<!-- Loader -->
<div id="preSecretariat  <div id="status">
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
                <a href="" class="logo text-center"><img width="80%" src="{{ \Illuminate\Support\Facades\URL::asset('assets/images/resources/deplogo.svg')}}"></a>
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

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>{{ __('Unit') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('divisionalSecretariat')}}">{{ __('Divisional Secretariat') }}</a></li>
                                <li><a href="{{route('divisionalSecretariat-view')}}">{{ __('Divisional Secretariat View') }}</a></li>
                                {{--<li><a href="{{route('pollingBooth')}}">{{ __('Municipal council') }}</a></li>--}}
                                {{--<li><a href="{{route('gramasewaDivision')}}">{{ __('Urban council') }}</a></li>--}}
                                {{--<li><a href="{{route('village')}}">{{ __('Divisional council') }}</a></li>--}}
                            </ul>
                        </li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 4)

                        <li class="menu-title">{{ __('ANALYSIS') }}</li>
                    @endif


                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)
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
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 4)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Post') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 )
                                    <li><a href="{{route('createPost')}}">{{ __('Create Post') }}</a></li>
                                @endif
                                <li><a href="{{route('viewPosts')}}">{{ __('View Posts') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)

                        {{--<li>--}}
                            {{--<a href="{{route('directMessages')}}" class="waves-effect"><i--}}
                                        {{--class="dripicons-suitcase"></i><span>{{ __('Direct Messages') }} </span></a>--}}
                        {{--</li>--}}

                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 2)
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


                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 5)

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="dripicons-suitcase"></i>
                                <span>{{ __('Analyze') }}
                                    <span class="pull-right">
                                       <i class="mdi mdi-chevron-right"></i>
                                   </span>
                               </span>
                            </a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('pendingResponse')}}">{{ __('Post Response') }}</a></li>
{{--                                <li><a href="{{route('assignStaff')}}">{{ __('Direct Messages') }}</a></li>--}}
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 4)

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="dripicons-suitcase"></i>
                                <span>{{ __('Reports') }}
                                    <span class="pull-right">
                                       <i class="mdi mdi-chevron-right"></i>
                                   </span>
                               </span>
                            </a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('report-categoryWise')}}">{{ __('Category Wise') }}</a></li>
                                <li><a href="{{route('report-locationWise')}}">{{ __('Location Wise') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)

                        <li class="menu-title">{{ __('ATTENDANCE') }}</li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="dripicons-suitcase"></i>
                                <span>{{ __('Event') }}
                                    <span class="pull-right">
                                       <i class="mdi mdi-chevron-right"></i>
                                   </span>
                               </span>
                            </a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('create-event')}}">{{ __('Create Event') }}</a></li>
                                <li><a href="{{route('view-events')}}">{{ __('View Events') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)

                        <li class="menu-title">{{ __('REPORTS') }}</li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect">
                            <i class="dripicons-suitcase"></i>
                            <span>{{ __('Reports') }}
                                <span class="pull-right">
                                       <i class="mdi mdi-chevron-right"></i>
                                   </span>
                               </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="{{route('report-agents')}}">{{ __('Agents Report') }}</a></li>
                            <li><a href="{{route('report-members')}}">{{ __('Member Report') }}</a></li>
                            <li><a href="{{route('report-age')}}">{{ __('Age Report') }}</a></li>
                            <li><a href="{{route('report-education')}}">{{ __('Education Report') }}</a></li>
                            <li><a href="{{route('report-income')}}">{{ __('Income Report') }}</a></li>
                            <li><a href="{{route('report-career')}}">{{ __('Career Report') }}</a></li>
                            <li><a href="{{route('report-religion')}}">{{ __('Religion Report') }}</a></li>
                            <li><a href="{{route('report-ethnicity')}}">{{ __('Ethnicity Report') }}</a></li>
                        </ul>
                    </li>
                    @endif

                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 3)

                    <li class="menu-title">{{ __('ADMINISTRATION') }}</li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 2)
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
                                <li><a href="{{route('createDefaultTask')}}">{{ __('Default Task') }}</a></li>
                                <li><a href="{{route('assignTask')}}">{{ __('Assign Task') }}</a></li>
                                <li><a href="{{route('viewTasks')}}">{{ __('View Task') }}</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <=3)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Users') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('addUser')}}">{{ __('Add Users') }}</a></li>
                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)

                                <li><a href="{{route('pendingAgents')}}">{{ __('Pending Users') }}</a></li>
                                @endif
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