@extends('layouts.main')
@section('psStyle')
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/morris/morris.css')}}">

    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">
                    <form id="form1" method="GET">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlert"
                                     style="display:none">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="electionDivision"
                                       class="control-label">{{ __('Election Division') }}</label>

                                <select name="electionDivision" id="electionDivision"
                                        class="select2 form-control "
                                        onchange="electionDivisionChanged(this);$('#form1').submit()"
                                >
                                    <option value="">ALL</option>

                                    @if($electionDivisions != null)
                                        @foreach($electionDivisions as $electionDivision)
                                            <option value="{{$electionDivision->idelection_division}}">{{strtoupper($electionDivision->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pollingBooth"
                                       class="control-label">{{ __('Polling Booth') }}</label>

                                <select name="pollingBooth" id="pollingBooth"
                                        class="select2 form-control "
                                        onchange="pollingBoothChanged(this);$('#form1').submit()"
                                >
                                    <option value="">ALL</option>


                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gramasewaDivision"
                                       class="control-label">{{ __('Gramasewa Divisions') }}</label>

                                <select name="gramasewaDivision" id="gramasewaDivision"
                                        class="select2 form-control "
                                        onchange="gramasewaDivisionChanged(this);$('#form1').submit()"
                                >
                                    <option value="">ALL</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="village"
                                       class="control-label">{{ __('Villages') }}</label>

                                <select name="village" id="village"  onchange="$('#form1').submit()"
                                        class="select2 form-control "
                                >
                                    <option value="">ALL</option>

                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2  ml-auto">
                                <button type="submit" form="form1"
                                        class="btn btn-success btn-block ">{{ __('Analyse Location') }}</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div> <!-- ./card -->
            <br/>
            <div class="card">
                <div class="card-body">
                    <div class="row" id="chartDiv">

                    </div>
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
@endsection
@section('psScript')
    <!--Morris Chart-->
    <script src="{{ URL::asset('assets/plugins/morris/morris.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/raphael/raphael-min.js')}}"></script>

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#form1').submit()
        });
        function electionDivisionChanged(el) {
            let divisions = $(el).val();
            $('#pollingBooth').html("<option value=''>ALL</option>");
            $('#gramasewaDivision').html("<option value=''>ALL</option>");
            $('#village').html("<option value=''>ALL</option>");

            $.ajax({
                url: '{{route('getPollingBoothByElectionDivision')}}',
                type: 'POST',
                data: {id: divisions},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#pollingBooth').append("<option value='" + value.idpolling_booth + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function pollingBoothChanged(el) {
            let booths = $(el).val();
            $('#gramasewaDivision').html("<option value=''>ALL</option>");
            $('#village').html("<option value=''>ALL</option>");

            $.ajax({
                url: '{{route('getGramasewaDivisionByPollingBooth')}}',
                type: 'POST',
                data: {id: booths},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#gramasewaDivision').append("<option value='" + value.idgramasewa_division + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function gramasewaDivisionChanged(el) {
            let division = el.value;
            $('#village').html("<option value=''>ALL</option>");
            $.ajax({
                url: '{{route('getVillageByGramasewaDivision')}}',
                type: 'POST',
                data: {id: division},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#village').append("<option value='" + value.idvillage + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        $("#form1").on("submit", function (event) {

            event.preventDefault();

            //initialize alert and variables
            $('.notify').empty();
            $('.alert').hide();
            $('.alert').html("");
            let completed = true;
            //initialize alert and variables end


            //validate user input

            //validation end

            if (completed) {

                $.ajax({
                    url: '{{route('report-locationWise')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlert').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert').append('<p><em class="fa fa-bullhorn"> ' + value + '</em></p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {
                            $('#chartDiv').html('');

                            let categories = data.success;
                            let posts = data.posts;
                            let mainArray = {};

                            $.each(categories, function (key, value) {
                                mainArray[key] = [value[0].category.category,0,0,0,0];
                                $.each(value, function (key1, value1) {
                                    if (value1.idsub_category == 1) {
                                        mainArray[key][1] += 1;
                                    }
                                    else if (value1.idsub_category == 2) {
                                        mainArray[key][2] += 1;

                                    } else if (value1.idsub_category == 3) {
                                        mainArray[key][3] += 1;

                                    }
                                });
                                //mainArray = [category name, questions, proposal, requests , beneficial]
                            });


                            $.each(posts, function (key, value) {
                                let cat = value.beneficial_category;
                                $.each(cat, function (catKey, catValue) {
                                    let cat = catValue.idcategory;

                                    if (mainArray[cat] !== undefined) {
                                        mainArray[cat][4] += 1;
                                    }
                                    else {
                                        mainArray[cat] = [catValue.category.category, 0, 0,0,1];
                                    }
                                });
                            });

                            $.each(mainArray, function (key, value) {
                                $('#chartDiv').append(
                                    "<div class='col-md-6 mb-5 text-center'>" +
                                    "<a href=\"{{route('viewPostsByCategory')}}?category="+key+"\"> <div class='text-secondary ' id='donut-" + key + "' style='height: 250px;font-weight: 700;'>" +
                                    "" + value[0] + "" +
                                    "</div></a>" +
                                    "</div>"
                                );
                            });

                            $.each(mainArray, function (key, value) {
                                new Morris.Donut({
                                    // ID of the element in which to draw the chart.
                                    element: 'donut-' + key + '',
                                    // Chart data records -- each entry in this array corresponds to a point on
                                    // the chart.
                                    data: [
                                        {
                                            year: '2008',
                                            value: value[1],
//                                            label: ''+value[0]+'',
                                            label: 'Questions',
                                            labelColor: '#ff353d'
                                        },
                                        {
                                            year: '2009',
                                            value:value[2],
                                            label: 'Proposal',
                                            labelColor: 'green'
                                        },
                                        {
                                            year: '2009',
                                            value:value[3],
                                            label: 'Requests',
                                            labelColor: 'green'
                                        },
                                        {
                                            year: '2009',
                                            value:value[4],
                                            label: 'Beneficial',
                                            labelColor: 'green'
                                        },
                                    ],
                                    labelColor: ["#9CC4E4"],
                                    colors: ['#E53935', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)']
                                });

                            });

                        }
                    }


                });
            }
            else {
                $('#errorAlert2').html('Please provide all required fields.');
                $('#errorAlert2').show();
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
            }
        });


    </script>
@endsection