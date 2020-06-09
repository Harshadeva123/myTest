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

                        <div id="jobDiv" class="form-group col-md-4">
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

                            <div class="form-group col-md-4 ">
                                <label for="taskType" style="margin-left: 5px;"
                                       class="control-label">{{ __('Task Type') }}</label>

                                <select class="form-control  " name="taskType"
                                        onchange="setCustomValidity('')"
                                        oninvalid="this.setCustomValidity('Please select task type')"
                                        id="taskType" required>
                                    <option value="" disabled selected>Select type</option>
                                    @foreach($taskTypes as $taskType)
                                        <option value="{{$taskType->idtask_type}}">{{$taskType->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group col-md-3 ">
                                <label for="totalBudget" style="margin-left: 5px;"
                                       class="control-label">{{ __('Total Budget') }}</label>
                                <div class="input-group">

                                    <input class="form-control " type="number"
                                           oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                           id="totalBudget"
                                           onchange="setCustomValidity('')"
                                           oninvalid="this.setCustomValidity('Please enter budget amount')"
                                           required name="totalBudget">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlert2"
                                     style="display:none">
                                </div>
                            </div>


                            {{--<div class="form-group col-md-4 ">--}}
                            {{--<label for="ageComparison" style="margin-left: 5px;"--}}
                            {{--class="control-label">{{ __('Age') }}</label>--}}
                            {{--<div class="input-group">--}}
                            {{--<div class="input-group-append">--}}
                            {{--<select class="form-control  " name="ageComparison"--}}
                            {{--onchange="ageChanged(this.value)"--}}
                            {{--id="ageComparison" required>--}}
                            {{--<option value="0" selected>EQUAL TO</option>--}}
                            {{--<option value="1">LOWER</option>--}}
                            {{--<option value="2">GRATER</option>--}}
                            {{--<option value="3">BETWEEN</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--<input class="form-control " type="number"--}}
                            {{--oninput="this.value = this.value < 0 ? 0 : this.value" min="0"--}}
                            {{--id="minAgeV" placeholder="Age"--}}
                            {{--name="minAge">--}}
                            {{--<input class="form-control " type="number"--}}
                            {{--oninput="this.value = this.value < 0 ? 0 : this.value" min="0"--}}
                            {{--id="maxAgeV" placeholder="Max Age"--}}
                            {{--name="maxAge">--}}

                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample1" aria-expanded="false"
                                            aria-controls="multiCollapseExample1"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary"><input id="isEthnicity" type="checkbox"
                                                                          name="isEthnicity" value="1"> Ethnicity
                                        </h5>

                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample1">
                                        @if($ethnicities != null)
                                            @foreach($ethnicities as $ethnicities)
                                                <div class="row">
                                                    <h6 class="col-md-3 ml-3">{{strtoupper($ethnicities->name_en)}}</h6>
                                                    <input data-id="{{$ethnicities->idethnicity}}"
                                                           class="form-control ethnicity col-md-2" type="number"
                                                           oninput="this.value = this.value < 0 ? 0 : this.value"
                                                           min="0"
                                                           id="members"
                                                           name="members">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample2" aria-expanded="false"
                                            aria-controls="multiCollapseExample2"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary"><input id="isReligion" type="checkbox"
                                                                          name="isReligion" value="1"> Religion
                                        </h5>

                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample2">
                                        @if($religions != null)
                                            @foreach($religions as $religion)
                                                <div class="row">
                                                    <h6 class="col-md-3 ml-3">{{strtoupper($religion->name_en)}}</h6>
                                                    <input data-id="{{$religion->idreligion}}"
                                                           class="form-control religions col-md-2" type="number"
                                                           oninput="this.value = this.value < 0 ? 0 : this.value"
                                                           min="0"
                                                           id="members"
                                                           name="members">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample3" aria-expanded="false"
                                            aria-controls="multiCollapseExample3"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary"><input id="isIncome" type="checkbox" name="isIncome"
                                                                          value="1"> Nature of income
                                        </h5>

                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample3">
                                        @if($incomes != null)
                                            @foreach($incomes as $income)
                                                <div class="row">
                                                    <h6 class="col-md-3 ml-3">{{strtoupper($income->name_en)}}</h6>
                                                    <input data-id="{{$income->idnature_of_income}}"
                                                           class="form-control incomes col-md-2" type="number"
                                                           oninput="this.value = this.value < 0 ? 0 : this.value"
                                                           min="0"
                                                           id="members"
                                                           name="members">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample4" aria-expanded="false"
                                            aria-controls="multiCollapseExample4"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary"><input id="isEducational" type="checkbox"
                                                                          name="isEducational" value="1"> Educational
                                            Qualification
                                        </h5>

                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample4">
                                        @if($educations != null)
                                            @foreach($educations as $education)
                                                <div class="row">
                                                    <h6 class="col-md-3 ml-3">{{strtoupper($education->name_en)}}</h6>
                                                    <input data-id="{{$education->ideducational_qualification}}"
                                                           class="form-control educations col-md-2" type="number"
                                                           oninput="this.value = this.value < 0 ? 0 : this.value"
                                                           min="0"
                                                           id="members"
                                                           name="members">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample5" aria-expanded="false"
                                            aria-controls="multiCollapseExample5"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary"><input id="isCareer" type="checkbox" name="isCareer"
                                                                          value="1"> Career
                                        </h5>

                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample5">
                                        @if($careers != null)
                                            @foreach($careers as $career)
                                                <div class="row">
                                                    <h6 class="col-md-3 ml-3">{{strtoupper($career->name_en)}}</h6>
                                                    <input data-id="{{$career->idcareer}}"
                                                           class="form-control careers col-md-2" type="number"
                                                           oninput="this.value = this.value < 0 ? 0 : this.value"
                                                           min="0"
                                                           id="members"
                                                           name="members">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample6" aria-expanded="false"
                                            aria-controls="multiCollapseExample6"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary">
                                            <input id="isGender" type="checkbox" name="isGender"
                                                   value="1"> Gender
                                        </h5>
                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample6">
                                        <div class="row">
                                            <h6 class="col-md-3 ml-3">Male</h6>
                                            <input data-id="1" class="form-control genders col-md-2" type="number"
                                                   oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                                   id="gender1"
                                                   name="gender">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <h6 class="col-md-3 ml-3">Female</h6>
                                            <input data-id="2" class="form-control  genders col-md-2" type="number"
                                                   oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                                   id="gender2"
                                                   name="gender">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <h6 class="col-md-3 ml-3">Other</h6>
                                            <input data-id="3" class="form-control  genders col-md-2" type="number"
                                                   oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                                   id="gender3"
                                                   name="gender">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample7" aria-expanded="false"
                                            aria-controls="multiCollapseExample7"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary"><input id="isJobSector" type="checkbox"
                                                                          name="isJobSector" value="1"> Job Sector
                                        </h5>
                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample7">
                                        <div class="row">
                                            <h6 class="col-md-3  ml-3">Government</h6>
                                            <input data-id="1" class="form-control jobSector col-md-2" type="number"
                                                   oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                                   id="members"
                                                   name="members">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <h6 class="col-md-3 ml-3">Private</h6>
                                            <input data-id="2" class="form-control jobSector col-md-2" type="number"
                                                   oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                                   id="members"
                                                   name="members">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <h6 class="col-md-3 ml-3">Non-Government</h6>
                                            <input data-id="3" class="form-control jobSector col-md-2" type="number"
                                                   oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                                   id="members"
                                                   name="members">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample8" aria-expanded="false"
                                            aria-controls="multiCollapseExample8"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary"><input id="isBranch" type="checkbox" name="isBranch"
                                                                          value="1"> Branch Society
                                        </h5>

                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample8">
                                        @if($positions != null)
                                            @foreach($positions as $position)
                                                <div class="row">
                                                    <h6 class="col-md-3 ml-3">{{strtoupper($position->name_en)}}</h6>
                                                    <input data-id="{{$position->idposition}}"
                                                           class="form-control branch col-md-2" type="number"
                                                           oninput="this.value = this.value < 0 ? 0 : this.value"
                                                           min="0"
                                                           id="members"
                                                           name="members">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample9" aria-expanded="false"
                                            aria-controls="multiCollapseExample9"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary"><input id="isWomens" type="checkbox" name="isWomens"
                                                                          value="1"> Woment's Society
                                        </h5>

                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample9">
                                        @if($positions != null)
                                            @foreach($positions as $position)
                                                <div class="row">
                                                    <h6 class="col-md-3 ml-3">{{strtoupper($position->name_en)}}</h6>
                                                    <input data-id="{{$position->idposition}}"
                                                           class="form-control womens col-md-2" type="number"
                                                           oninput="this.value = this.value < 0 ? 0 : this.value"
                                                           min="0"
                                                           id="members"
                                                           name="members">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 data-toggle="collapse"
                                            href="#multiCollapseExample10" aria-expanded="false"
                                            aria-controls="multiCollapseExample10"
                                            title="This task will automatically assign to all agent when they were approved."
                                            class="text-secondary"><input id="isYouth" type="checkbox" name="isYouth"
                                                                          value="1"> Youth Society
                                        </h5>

                                    </div>
                                    <div class="col-md-12 collapse multi-collapse" id="multiCollapseExample10">
                                        @if($positions != null)
                                            @foreach($positions as $position)
                                                <div class="row">
                                                    <h6 class="col-md-3 ml-3">{{strtoupper($position->name_en)}}</h6>
                                                    <input data-id="{{$position->idposition}}"
                                                           class="form-control youth col-md-2" type="number"
                                                           oninput="this.value = this.value < 0 ? 0 : this.value"
                                                           min="0"
                                                           id="members"
                                                           name="members">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
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
                                        class="btn btn-primary float-right btn-block ">{{ __('Save Budget') }}
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
            {{--if({{$default != null ? 1 : 0}}){--}}
            //                goFirstPage();
            //                viewDefault();
            //            }
            //            else{
            goSecondPage();
//            }
        });


        function clearAll() {
            $('input').not(':checkbox').not('.noClear').not(':radio').val('');
            $(":checkbox").attr('checked', false).trigger('change');
            $(":radio").attr('checked', false).trigger('change');
            $('select').val('').trigger('change');
            $('#searchCol').val(1).trigger('change');
            $('#ageComparison').val(1).trigger('change');
            $('.collapse').collapse("hide");
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

            let ethnicityArray = [];
            let religionArray = [];
            let educationArray = [];
            let incomeArray = [];
            let careerArray = [];
            let genderArray = [];
            let jobSectorArray = [];
            let branchArray = [];
            let womenArray = [];
            let youthArray = [];


            if ($('#isEthnicity').prop('checked') == 1) {
                $('.ethnicity').each(function () {
                    if (this.value != '') {
                        ethnicityArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                })
            }

            if ($('#isReligion').prop('checked') == 1) {
                $('.religions').each(function () {
                    if (this.value != '') {

                        religionArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                })
            }

            if ($('#isEducational').prop('checked') == 1) {
                $('.educations').each(function () {
                    if (this.value != '') {

                        educationArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                })
            }

            if ($('#isCareer').prop('checked') == 1) {
                $('.careers').each(function () {
                    if (this.value != '') {
                        careerArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                })
            }

            if ($('#isIncome').prop('checked') == 1) {
                $('.incomes').each(function () {
                    if (this.value != '') {
                        incomeArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                })
            }

            if ($('#isGender').prop('checked') == 1) {
                $('.genders').each(function () {
                    if (this.value != '') {
                        genderArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                });
            }

            if ($('#isJobSector').prop('checked') == 1) {
                $('.jobSector').each(function () {
                    if (this.value != '') {
                        jobSectorArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                })
            }

            if ($('#isBranch').prop('checked') == 1) {
                $('.branch').each(function () {
                    if (this.value != '') {
                        branchArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                })
            }

            if ($('#isWomens').prop('checked') == 1) {
                $('.womens').each(function () {
                    if (this.value != '') {
                        womenArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                })
            }

            if ($('#isYouth').prop('checked') == 1) {
                $('.youth').each(function () {
                    if (this.value != '') {
                        youthArray.push({'value': this.value, 'id': $(this).attr('data-id')});
                    }
                })
            }


            //initialize alert and variables
            $('.notify').empty();
            $('.alert').hide();
            $('.alert').html("");
            let completed = true;

            let totalBudget = $('#totalBudget').val();
            let taskType = $('#taskType').val();
            let isEthnicity = $('#isEthnicity').prop('checked');
            let isReligion = $('#isReligion').prop('checked');
            let isEducational = $('#isEducational').prop('checked');
            let isIncome = $('#isIncome').prop('checked');
            let isCareer = $('#isCareer').prop('checked');
            let isGender = $('#isGender').prop('checked');
            let isJobSector = $('#isJobSector').prop('checked');
            let isBranch = $('#isBranch').prop('checked');
            let isWomens = $('#isWomens').prop('checked');
            let isYouth = $('#isYouth').prop('checked');
            //initialize alert and variables end


            //validate user input

            //validation end

            if (completed) {

                $.ajax({
                    url: '{{route('saveTask')}}',
                    type: 'POST',
                    data: {
                        totalBudget: totalBudget,
                        taskType: taskType,
                        isEthnicity: isEthnicity,
                        isReligion: isReligion,
                        isCareer: isCareer,
                        isIncome: isIncome,
                        isEducational: isEducational,
                        isGender: isGender,
                        isJobSector: isJobSector,
                        isBranch: isBranch,
                        isWomens: isWomens,
                        isYouth: isYouth,
                        ethnicityArray: ethnicityArray,
                        religionArray: religionArray,
                        incomeArray: incomeArray,
                        educationArray: educationArray,
                        careerArray: careerArray,
                        jobSectorArray: jobSectorArray,
                        genderArray: genderArray,
                        branchArray: branchArray,
                        womenArray: womenArray,
                        youthArray: youthArray
                    },
                    success: function (data) {

                        notify({
                            type: "success", //alert | success | error | warning | info
                            title: 'DEFAULT TASK SAVED!',
                            autoHide: true, //true | false
                            delay: 2500, //number ms
                            position: {
                                x: "right",
                                y: "top"
                            },
                            icon: '<em class="mdi mdi-check-circle-outline"></em>',

                            message: 'Default task details saved successfully.'
                        });
                        clearAll();
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


            $('#membersV').html('<h5>' + result.target + '</h5>');
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

            if (result.task_gender == 0) {
                $('#genderValueDiv').html("<em  class='mdi mdi-human-male-female  mdi-24px' >&nbsp;Any</em>");
            }
            else if (result.task_gender == 1) {
                $('#genderValueDiv').html("<em  class='mdi mdi-human-male  mdi-24px' >&nbsp;Male</em>");
            }
            else if (result.task_gender == 2) {
                $('#genderValueDiv').html("<em  class='mdi mdi-human-female  mdi-24px' >&nbsp;Female</em>");
            }
            else {
                $('#genderValueDiv').html("<em  class='mdi mdi-human-pregnant  mdi-24px' >&nbsp;Other</em>");
            }

            if (result.task_job_sector == 0) {
                $('#jobSectorDiv').html("<em  class='fa fa-circle-o' style='color: #1295a0;;font-size: 1.3em;' ></em>&nbsp;<span style='font-weight:500;font-size: 1.3em;'>Any</span>");
            }
            else if (result.task_job_sector == 1) {
                $('#jobSectorDiv').html("<em  class='fa fa-circle-o' style='color: #2ca02c;;font-size: 1.3em;' ></em>&nbsp;<span style='font-weight:500;font-size: 1.3em;'>Government</span>");
            }
            else if (result.task_job_sector == 2) {
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