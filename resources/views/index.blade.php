@extends('layouts.main')
@section('psStyle')
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/morris/morris.css')}}">
@endsection
@section('psContent')


                    <!-- ==================
                         PAGE CONTENT START
                         ================== -->

                    <div class="page-content-wrapper">

                        <div class="header-bg">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 mb-4 pt-5">
                                        <div id="morris-bar-example" class="dash-chart"></div>

                                        <div class="mt-4 text-center">
                                            <button type="button" class="btn btn-outline-primary ml-1 waves-effect waves-light">Year 2017</button>
                                            <button type="button" class="btn btn-outline-info ml-1 waves-effect waves-light">Year 2018</button>
                                            <button type="button" class="btn btn-outline-primary ml-1 waves-effect waves-light">Year 2019</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 col-xl-3">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-info">15,852</h3>
                                            Agent Count
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-purple">9,514</h3>
                                           Member Count
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-primary">289</h3>
                                            Events Count
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="card text-center m-b-30">
                                        <div class="mb-2 card-body text-muted">
                                            <h3 class="text-danger">01/06/2020</h3>
                                           Next Payment Date
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row">

                                <div class="col-xl-4">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">District Ethnicity</h4>

                                            <div class="row text-center m-t-20">
                                                <div class="col-6">
                                                    <h5 class="">56241</h5>
                                                    <p class="text-muted font-14">Agents</p>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class="">23651</h5>
                                                    <p class="text-muted font-14">Members</p>
                                                </div>
                                            </div>
                                            <div id="myfirstchart" class="dash-chart" style="height: 250px;"></div>
                                            {{--<div id="morris-donut-example" class="dash-chart"></div>--}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-8">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Office Storage</h4>

                                            <div class="row text-center m-t-20">
                                                <div class="col-4">
                                                    <h5 class="">56241</h5>
                                                    <p class="text-muted font-14">Post</p>
                                                </div>
                                                <div class="col-4">
                                                    <h5 class="">23651</h5>
                                                    <p class="text-muted font-14">Response</p>
                                                </div>
                                                <div class="col-4">
                                                    <h5 class="">23651</h5>
                                                    <p class="text-muted font-14">Direct Messages</p>
                                                </div>
                                            </div>

                                            <div id="morris-area-example" class="dash-chart"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- end row -->

                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 m-b-30 header-title">Latest Post</h4>

                                            <div class="table-responsive">
                                                <table class="table m-t-20 mb-0 table-vertical">

                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::asset('assets/images/users/avatar-2.jpg')}}" alt="user-image" class="thumb-sm rounded-circle mr-2"/>
                                                            Saman C. Perera
                                                        </td>
                                                        <td><em class="mdi mdi-checkbox-blank-circle text-success"></em> Active</td>
                                                        <td>
                                                            $14,584
                                                            <p class="m-0 text-muted font-14">Comments</p>
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                            <p class="m-0 text-muted font-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::asset('assets/images/users/avatar-3.jpg')}}" alt="user-image" class="thumb-sm rounded-circle mr-2"/>
                                                            Sanath N. Chadrasiri
                                                        </td>
                                                        <td><em class="mdi mdi-checkbox-blank-circle text-warning"></em> Expired</td>
                                                        <td>
                                                            $14,584
                                                            <p class="m-0 text-muted font-14">Comments</p>
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                            <p class="m-0 text-muted font-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::asset('assets/images/users/avatar-4.jpg')}}" alt="user-image" class="thumb-sm rounded-circle mr-2"/>
                                                            Supun S. Wijesiri
                                                        </td>
                                                        <td><em class="mdi mdi-checkbox-blank-circle text-success"></em> Active</td>
                                                        <td>
                                                            $14,584
                                                            <p class="m-0 text-muted font-14">Comments</p>
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                            <p class="m-0 text-muted font-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::asset('assets/images/users/avatar-5.jpg')}}" alt="user-image" class="thumb-sm rounded-circle mr-2"/>
                                                            Mohan C. Palliyaguru
                                                        </td>
                                                        <td><em class="mdi mdi-checkbox-blank-circle text-danger"></em> Deleted</td>
                                                        <td>
                                                            $14,584
                                                            <p class="m-0 text-muted font-14">Comments</p>
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                            <p class="m-0 text-muted font-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::asset('assets/images/users/avatar-6.jpg')}}" alt="user-image" class="thumb-sm rounded-circle mr-2"/>
                                                            Kamal S. Gunasekara
                                                        </td>
                                                        <td><em class="mdi mdi-checkbox-blank-circle text-warning"></em> Expired</td>
                                                        <td>
                                                            $14,584
                                                            <p class="m-0 text-muted font-14">Comments</p>
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                            <p class="m-0 text-muted font-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect">Edit</button>
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 m-b-15 header-title">Recent Received Comments</h4>

                                            <ol class="activity-feed mb-0">
                                                <li class="feed-item">
                                                    <span class="date">Sep 25</span>
                                                    <span class="activity-text">Excellent post.I like this.</span>
                                                </li>

                                                <li class="feed-item">
                                                    <span class="date">Sep 24</span>
                                                    <span class="activity-text">Can we extend it into our village.</span>
                                                </li>
                                                <li class="feed-item">
                                                    <span class="date">Sep 23</span>
                                                    <span class="activity-text">Bad post.I don't like this.</span>
                                                </li>
                                                <li class="feed-item">
                                                    <span class="date">Sep 21</span>
                                                    <span class="activity-text">I have some problem in my village.</span>
                                                </li>
                                                <li class="feed-item">
                                                    <span class="date">Sep 18</span>
                                                    <span class="activity-text">Yes we do it.</span>
                                                </li>
                                                <li class="feed-item">
                                                    <span class="date">Sep 17</span>
                                                    <span class="activity-text">Excellent post.I like this.</span>
                                                </li>
                                                <li class="feed-item pb-1">
                                                    <span class="activity-text">
                                                        <a href="" class="text-primary">More Activities</a>
                                                    </span>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!-- end row -->

                         </div>


                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->

@endsection
@section('psScript')

    <!--Morris Chart-->
    <script src="{{ URL::asset('assets/plugins/morris/morris.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/raphael/raphael-min.js')}}"></script>
    <script src="{{ URL::asset('assets/pages/dashborad.js')}}"></script>

    <script>
        new Morris.Donut({
            // ID of the element in which to draw the chart.
            element: 'myfirstchart',
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: [
                { year: '2008', value: 60, label:'Sinhala' },
                { year: '2009', value: 10, label:'Muslim' },
                { year: '2012', value: 15, label:'Tamil' },
                { year: '2012', value: 15, label:'Burgher' }
            ],
            // The name of the data record attribute that contains x-values.
            xkey: 'year',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['value'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['Value']
        });
    </script>
@endsection