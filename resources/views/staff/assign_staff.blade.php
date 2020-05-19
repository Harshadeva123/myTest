@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table class="table table-striped table-bordered"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>FULL NAME</th>
                                            <th># OF ASSIGNED</th>
                                            <th  style="text-align: center;">OPTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($users))
                                            @if(count($users) > 0)
                                                @foreach($users as $user)
                                                    <tr id="{{$user->idUser}}">
                                                        <td>{{$user->userTitle->name_en}} {{$user->fName}} {{$user->lName}}</td>
                                                        <td>4</td>
                                                        <td style="text-align: center;">
                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                                        type="button" id="dropdownMenuButton"
                                                                        data-toggle="dropdown"
                                                                        aria-haspopup="true"
                                                                        aria-expanded="false">
                                                                    Option
                                                                </button>

                                                                <div class="dropdown-menu"
                                                                     aria-labelledby="dropdownMenuButton">
                                                                    <a href="#"
                                                                       onclick="assignStaff({{$user->idUser}})"
                                                                       class="dropdown-item">Assign
                                                                    </a>

                                                                </div>


                                                            </div>
                                                        </td>
                                                    </tr>

                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10"
                                                        style="text-align: center;font-weight: 500">Sorry No
                                                        Results Found.
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(isset($users))
                        {{$users->links()}}
                    @endif

                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->


    <!-- modal start -->
    <div class="modal fade" id="assignModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Assign Staff</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="electionDivisions"
                                           class="control-label">{{ __('Election Divisions') }}</label>

                                    <select name="electionDivisions[]" id="electionDivisions"
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
                                            onchange="electionDivisionChanged(this)"
                                            data-placeholder="Choose ...">
                                        @if($electionDivisions != null)
                                            @foreach($electionDivisions as $electionDivision)
                                                <option value="{{$electionDivision->idelection_division}}">{{strtoupper($electionDivision->name_en)}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="pollingBooths"
                                           class="control-label">{{ __('Polling Booths') }}</label>

                                    <select name="pollingBooths[]" id="pollingBooths"
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
                                            onchange="pollingBoothChanged(this)"
                                            data-placeholder="Choose ...">

                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="gramasewaDivisions"
                                           class="control-label">{{ __('Gramasewa Divisions') }}</label>

                                    <select name="gramasewaDivisions[]" id="gramasewaDivisions"
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
                                            data-placeholder="Choose ...">

                                    </select>
                                </div>

                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- modal end -->
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

        function assignStaff(id) {
            $('#assignModal').modal('show');
        }

        function electionDivisionChanged(el) {
            let divisions = $(el).val();
            $('#pollingBooths').html('');
            $('#gramasewaDivisions').html('');
            $('#villages').html('');

            $.ajax({
                url: '{{route('getPollingBoothByElectionDivisions')}}',
                type: 'POST',
                data: {id: divisions},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#pollingBooths').append("<option value='" + value.idpolling_booth + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function pollingBoothChanged(el) {
            let booths = $(el).val();
            $('#gramasewaDivisions').html('');
            $('#villages').html('');

            $.ajax({
                url: '{{route('getGramasewaDivisionByPollingBooths')}}',
                type: 'POST',
                data: {id: booths},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#gramasewaDivisions').append("<option value='" + value.idgramasewa_division + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

    </script>
@endsection