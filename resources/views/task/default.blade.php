@extends('layouts.main')
@section('psStyle')
    <style>

        #membersV {
            height: 50px;
            width: 50px;
            border-radius: 10px;
            border: solid 1px #4c7bff;
            text-align: center;
        }
    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="card firstPage">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9 .d-sm-none ">
                        </div>
                        <div class="col-md-3  p-sm-2">
                            <button onclick="goSecondPage();"
                                    class="btn btn-primary float-right btn-block ">{{ __('Change Default Task') }}
                            </button>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-3 ">
                            <div id="membersV"></div>
                            <label for="membersV" style="margin-left: 5px;"
                                   class="control-label">{{ __('Target') }}</label>
                        </div>
                        <div id="ageDiv" class="form-group col-md-4 ">
                            <label for="ageComparisonV" style="margin-left: 5px;"
                                   class="control-label">{{ __('Age') }}</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <select class="form-control  " name="ageComparisonV"
                                            onchange="ageChangedV(this.value)"
                                            readonly disabled id="ageComparisonV" required>
                                        <option value="" selected></option>
                                        <option value="0">EQUAL TO</option>
                                        <option value="1">LESS THAN</option>
                                        <option value="2">GRATER THAN</option>
                                        <option value="3">BETWEEN</option>
                                    </select>
                                </div>
                                <input class="form-control " type="number"
                                       readonly
                                       id="minAgeV" placeholder=""
                                       name="minAgeV">
                                <input style="display: none;" class="form-control " type="number"
                                       readonly
                                       id="maxAgeV" placeholder=""
                                       name="maxAgeV">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="ethnicityDiv" class="form-group col-md-4">
                            <label for="ethnicitiesV"
                                   class="control-label">{{ __('Ethnicit') }}</label>

                            <div id="ethnicitiesV"></div>
                        </div>
                        <div id="religionsDiv" class="form-group col-md-4">
                            <label for="religionsV"
                                   class="control-label">{{ __('Religion') }}</label>

                            <div id="religionsV"></div>
                        </div>
                        <div id="incomesDiv" class="form-group col-md-4">
                            <label for="incomesV"
                                   class="control-label">{{ __('Nature of income') }}</label>

                            <div id="incomesV"></div>
                        </div>
                        <div id="educationsDiv" class="form-group col-md-4">
                            <label for="educationsV"
                                   class="control-label">{{ __('Educational Qualification') }}</label>

                            <div id="educationsV"></div>
                        </div>
                        <div id="careerDiv" class="form-group col-md-8">
                            <label for="careersV"
                                   class="control-label">{{ __('Career') }}</label>

                            <div id="careersV"></div>
                        </div>
                        <div id="genderDiv" class="form-group col-md-4">
                            <label style="margin-left: 5px;"
                                   class="control-label">{{ __('Gender') }}</label>
                            <div id="genderValueDiv"></div>
                        </div>

                        <div id="jobDiv"  class="form-group col-md-4">
                            <label style="margin-left: 5px;"
                                   class="control-label">{{ __('Job Sector') }}</label>
                            <div id="jobSectorDiv"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card secondPage">
                <form id="form1" method="GET">

                    <div class="card-body">
                        <div class="row secondPage">
                            <div class="col-md-10">
                                <h5 title="This task will automatically assign to all agent when they were approved."
                                    class="text-secondary"><em class="fa fa-tasks"></em> Create Default Task
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
                                <label for="ageComparison" style="margin-left: 5px;"
                                       class="control-label">{{ __('Age') }}</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <select class="form-control  " name="ageComparison"
                                                onchange="ageChanged(this.value)"
                                                id="ageComparison" required>
                                            <option value="0" selected>EQUAL TO</option>
                                            <option value="1">LOWER</option>
                                            <option value="2">GRATER</option>
                                            <option value="3">BETWEEN</option>
                                        </select>
                                    </div>
                                    <input class="form-control " type="number"
                                           oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                           id="minAgeV" placeholder="Age"
                                           name="minAge">
                                    <input class="form-control " type="number"
                                           oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                           id="maxAgeV" placeholder="Max Age"
                                           name="maxAge">

                                </div>
                            </div>

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
                                    <label style="margin-left: 10px;" class="radio-inline gender"><input
                                                style="margin-left: 10px;" type="radio" value="0" name="gender"
                                                checked>&nbsp;{{ __('Any') }}
                                    </label>
                                    <label style="margin-left: 5px;" class="radio-inline gender"><input
                                                style="margin-left: 5px;" type="radio" value="1"
                                                name="gender">&nbsp;{{ __('Male') }}
                                    </label> &nbsp;
                                    &nbsp;
                                    <label style="margin-left: 5px;" class="radio-inline gender"><input
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
                                    <label style="margin-left: 10px;" class="radio-inline sector"><input
                                                style="margin-left: 10px;" type="radio" value="0"
                                                name="jobSector"
                                                checked>&nbsp;{{ __('Any') }}
                                    </label>
                                    <label style="margin-left: 5px;" class="radio-inline sector"><input
                                                style="margin-left: 5px;" type="radio" value="1"
                                                name="jobSector">&nbsp;{{ __('Government') }}
                                    </label> &nbsp;
                                    &nbsp;
                                    <label style="margin-left: 5px;" class="radio-inline sector"><input
                                                style="margin-left: 5px;" type="radio" value="2"
                                                name="jobSector">&nbsp;{{ __('Private') }}</label>
                                </div>
                            </div>
                        </div>
                        <input name="userId" id="userId" value="0" class="noClear" type="hidden">
                        <input name="isDefault" id="isDefault" value="1" class="noClear" type="hidden">
                        <div class="row secondPage">
                            <div class="col-md-8 .d-sm-none ">
                            </div>
                            <div class="col-md-2  p-sm-2">
                                <button onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger float-right btn-block ">{{ __('Cancel') }} </button>
                            </div>
                            <div class="col-md-2  p-sm-2">
                                <button form="form1" type="submit"
                                        class="btn btn-primary float-right btn-block ">{{ __('Assign Task') }}
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
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

            $('.secondPage').hide();
            if({{$default != null ? 1 : 0}}){
                goFirstPage();
                viewDefault();
            }
            else{
                goSecondPage();
            }
        });


        function clearAll() {
            $('input').not(':checkbox').not('.noClear').not(':radio').val('');
            $(":checkbox").attr('checked', false).trigger('change');
            $(":radio").attr('checked', false).trigger('change');
            $('select').val('').trigger('change');
            $('#searchCol').val(1).trigger('change');
            $('#ageComparison').val(1).trigger('change');
        }

        function ageChangedV(id) {
            if (id == 3) {
                $('#maxAgeV').show();
            } else {
                $('#maxAgeV').hide();
            }
        }


        function goSecondPage() {
            $('.secondPage').fadeIn();
            $('.firstPage').fadeOut();
        }

        function goFirstPage() {
            $('.secondPage').fadeOut();
            $('.firstPage').fadeIn();
        }

        function ageChanged(id) {
            if (id == 3) {
                $('#maxAge').show();
                $('#minAge').attr('placeholder', 'Min age');
            } else {
                $('#maxAge').hide();
                $('#minAge').attr('placeholder', 'Age');
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
                            location.reload();
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

        function viewDefault() {
            $('#ethnicitiesV').html('');
            $('#careersV').html('');
            $('#religionsV').html('');
            $('#incomesV').html('');
            $('#educationsV').html('');
//
            @if(isset($default) && $default != null)
            let result = {!! $default !!};
            @else
            let result = [];
            @endif


            $('#membersV').html('<h5>'+result.target+'</h5>' );
            $('#taskNoV').val(result.task_no);
            if (result.age != null) {
                $('#ageComparisonV').val(result.age.comparison).trigger('change');
                $('#minAgeV').val(result.age.minAge);
                $('#maxAgeV').val(result.age.maxAge);
            }
            else {
                $('#ageComparisonV').val('').trigger('change');
                $('#minAgeV').val('');
                $('#maxAgeV').val('');
                $('#maxAgeV').val('');
                $('#ageDiv').hide();
            }

            if(result.task_gender == 0){
                $('#genderValueDiv').html("<em  class='mdi mdi-human-male-female  mdi-24px' >&nbsp;Any</em>");
            }
            else if(result.task_gender == 1){
                $('#genderValueDiv').html("<em  class='mdi mdi-human-male  mdi-24px' >&nbsp;Male</em>");
            }
            else if(result.task_gender == 2){
                $('#genderValueDiv').html("<em  class='mdi mdi-human-female  mdi-24px' >&nbsp;Female</em>");
            }
            else {
                $('#genderValueDiv').html("<em  class='mdi mdi-human-pregnant  mdi-24px' >&nbsp;Other</em>");
            }

            if(result.task_job_sector == 0){
                $('#jobSectorDiv').html("<em  class='fa fa-circle-o' style='color: #1295a0;;font-size: 1.3em;' ></em>&nbsp;<span style='font-weight:500;font-size: 1.3em;'>Any</span>");
            }
            else if(result.task_job_sector == 1){
                $('#jobSectorDiv').html("<em  class='fa fa-circle-o' style='color: #2ca02c;;font-size: 1.3em;' ></em>&nbsp;<span style='font-weight:500;font-size: 1.3em;'>Government</span>");
            }
            else if(result.task_job_sector == 2){
                $('#jobSectorDiv').html("<em  class='fa fa-circle-o' style='color: #9da009;;font-size: 1.3em;' ></em>&nbsp;<span style='font-weight:500;font-size: 1.3em;'>Private</span>");
            }
            else {
                $('#jobSectorDiv').html("<em  class='fa fa-circle-o' style='color: #8d0aa0;;font-size: 1.3em;' ></em>&nbsp;<span style='font-weight:500;font-size: 1.3em;'>Non-Government</span>");
            }

            if (result.ethnicities.length > 0) {
                $.each(result.ethnicities, function (key, value) {
                    $('#ethnicitiesV').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.ethnicity.name_en + '</p>');
                });
            } else {
                $('#ethnicityDiv').hide();
            }
            if (result.careers.length > 0) {
                $.each(result.careers, function (key, value) {
                    $('#careersV').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.career.name_en + '</p>');
                });
            } else {
                $('#careerDiv').hide();
            }
            if (result.religions.length > 0) {
                $.each(result.religions, function (key, value) {
                    $('#religionsV').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.religion.name_en + '</p>');
                });
            } else {
                $('#religionsDiv').hide();
            }
            if (result.incomes.length > 0) {
                $.each(result.incomes, function (key, value) {
                    $('#incomesV').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.income.name_en + '</p>');
                });
            } else {
                $('#incomesDiv').hide();
            }
            if (result.educations.length > 0) {
                $.each(result.educations, function (key, value) {
                    $('#educationsV').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.education.name_en + '</p>');
                });
            } else {
                $('#educationsDiv').hide();
            }
        }
    </script>
@endsection