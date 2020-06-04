@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">


            <div  class="card secondPage">
                <form  id="form1" method="GET">

                    <div class="card-body">
                        <div class="row secondPage">
                            <div class="col-md-10">
                                <h5 title="This task will automatically assign to all agent when they were approved." class="text-secondary"><em class="fa fa-tasks"></em> Create Default Task
                                </h5>

                            </div>
                            <div class="col-md-12">
                                <hr/>
                            </div>
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlert2"
                                     style="display:none">
                                </div>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="members" style="margin-left: 5px;"
                                       class="control-label">{{ __('Number of Members') }}</label>
                                <input class="form-control " type="number"
                                       oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                       id="members"
                                       name="members">
                            </div>
                            <div class="form-group col-md-4 ">
                                <label for="ageComparison" style="margin-left: 5px;" class="control-label">{{ __('Age') }}</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <select class="form-control  " name="ageComparison" onchange="ageChanged(this.value)"
                                                id="ageComparison" required>
                                            <option value="0" selected>EQUAL TO</option>
                                            <option value="1">LOWER </option>
                                            <option value="2">GRATER </option>
                                            <option value="3">BETWEEN</option>
                                        </select>
                                    </div>
                                    <input class="form-control " type="number"
                                           oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                           id="minAge" placeholder="Age"
                                           name="minAge">
                                    <input  class="form-control " type="number"
                                           oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                           id="maxAge" placeholder="Max Age"
                                           name="maxAge">

                                </div>
                            </div>
                            <input type="hidden" id="userId" name="userId" value="1">
                            <input type="hidden" id="isDefault" name="isDefault" value="1">

                            <div class="form-group col-md-6">
                                <label for="ethnicities"
                                       class="control-label">{{ __('Ethnicity') }}</label>

                                <select name="ethnicities[]" id="ethnicities"
                                        class="select2 form-control select2-multiple" multiple="multiple"
                                        multiple
                                        data-placeholder="Choose ...">
                                    @if($ethnicities != null)
                                        @foreach($ethnicities as $ethnicities)
                                            <option value="{{$ethnicities->idethnicity}}">{{strtoupper($ethnicities->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="religions"
                                       class="control-label">{{ __('Religion') }}</label>

                                <select name="religions[]" id="religions"
                                        class="select2 form-control select2-multiple" multiple="multiple"
                                        multiple
                                        data-placeholder="Choose ...">
                                    @if($religions != null)
                                        @foreach($religions as $religion)
                                            <option value="{{$religion->idreligion}}">{{strtoupper($religion->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="incomes"
                                       class="control-label">{{ __('Nature of income') }}</label>

                                <select name="incomes[]" id="incomes"
                                        class="select2 form-control select2-multiple" multiple="multiple"
                                        multiple
                                        data-placeholder="Choose ...">
                                    @if($incomes != null)
                                        @foreach($incomes as $income)
                                            <option value="{{$income->idnature_of_income}}">{{strtoupper($income->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="educations"
                                       class="control-label">{{ __('Educational Qualification') }}</label>

                                <select name="educations[]" id="educations"
                                        class="select2 form-control select2-multiple" multiple="multiple"
                                        multiple
                                        data-placeholder="Choose ...">
                                    @if($incomes != null)
                                        @foreach($educations as $education)
                                            <option value="{{$education->ideducational_qualification}}">{{strtoupper($education->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="careers"
                                       class="control-label">{{ __('Career') }}</label>

                                <select name="careers[]" id="careers"
                                        class="select2 form-control select2-multiple" multiple="multiple"
                                        multiple
                                        data-placeholder="Choose ...">
                                    @if($careers != null)
                                        @foreach($careers as $career)
                                            <option value="{{$career->idcareer}}">{{strtoupper($career->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label style="margin-left: 5px;"
                                       class="control-label">{{ __('Gender') }}</label>
                                <div class="row">
                                    <label style="margin-left: 10px;" class="radio-inline"><input
                                                style="margin-left: 10px;" type="radio" value="0" name="gender"
                                                checked>&nbsp;{{ __('Any') }}
                                    </label>
                                    <label style="margin-left: 5px;" class="radio-inline"><input
                                                style="margin-left: 5px;" type="radio" value="1"
                                                name="gender">&nbsp;{{ __('Male') }}
                                    </label> &nbsp;
                                    &nbsp;
                                    <label style="margin-left: 5px;" class="radio-inline"><input
                                                style="margin-left: 5px;" type="radio" value="2"
                                                name="gender">&nbsp;{{ __('Female') }}</label>
                                    <label style="margin-left: 5px;" class="radio-inline"><input
                                                style="margin-left: 5px;" type="radio" value="3"
                                                name="gender">&nbsp;{{ __('Other') }}</label>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label style="margin-left: 5px;"
                                       class="control-label">{{ __('Job Sector') }}</label>
                                <div class="row">
                                    <label style="margin-left: 10px;" class="radio-inline"><input
                                                style="margin-left: 10px;" type="radio" value="0"
                                                name="jobSector"
                                                checked>&nbsp;{{ __('Any') }}
                                    </label>
                                    <label style="margin-left: 5px;" class="radio-inline"><input
                                                style="margin-left: 5px;" type="radio" value="1"
                                                name="jobSector">&nbsp;{{ __('Government') }}
                                    </label> &nbsp;
                                    &nbsp;
                                    <label style="margin-left: 5px;" class="radio-inline"><input
                                                style="margin-left: 5px;" type="radio" value="2"
                                                name="jobSector">&nbsp;{{ __('Private') }}</label>
                                </div>
                            </div>
                        </div>
                        <div  class="row secondPage">
                            <div class="col-md-8 .d-sm-none ">
                            </div>
                            <div class="col-md-2  p-sm-2">
                                <button onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger float-right btn-block ">{{ __('Cancel') }} </button>
                            </div>
                            <div class="col-md-2  p-sm-2">
                                <button form="form1" type="submit" class="btn btn-primary float-right btn-block ">{{ __('Assign Task') }}
                                </button>
                            </div>

                        </div>
                    </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function clearAll() {
            $('input').not(':checkbox').not(':radio').val('');
            $(":checkbox").attr('checked', false).trigger('change');
            $(":radio").attr('checked', false).trigger('change');
            $('select').val('').trigger('change');
            $('#searchCol').val(1).trigger('change');
            $('#ageComparison').val(1).trigger('change');
        }

        function selectAgent(id) {
            $('.firstPage').fadeOut();
            $('.secondPage').fadeIn();
            $('#userId').val(id);
            $('#agentName').html($('#'+id).find("td").eq(0).html());
        }

        function showFistPage() {
            $('.secondPage').fadeOut();
            $('.firstPage').fadeIn();
            clearAll();
        }

        function ageChanged(id) {
            if(id == 3){
                $('#maxAge').show();
                $('#minAge').attr('placeholder','Min age');
            }else {
                $('#maxAge').hide();
                $('#minAge').attr('placeholder','Age');
            }
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
                    url: '{{route('saveTask')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlert2').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert2').append('<p>' + value + '</p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'NEW TASK ASSIGNED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Task assigned successfully.'
                            });
                            clearAll();
                            showFistPage();
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