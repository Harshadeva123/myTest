@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <form action="{{route('pendingAgents')}}" method="GET">
                                <div class="row">
                                    {{ csrf_field() }}


                                    <div class="form-group col-md-4 ">
                                        <label for="searchCol">Search By</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <select class="form-control  " name="searchCol"
                                                        id="searchCol" required>
                                                    <option value="1" selected>FIRST NAME</option>
                                                    <option value="2" >LAST NAME</option>
                                                    <option value="3">NIC NO</option>
                                                </select>
                                            </div>
                                            <input class="form-control " type="text" min="0" id="searchText"
                                                   name="searchText">

                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="gender">By Gender</label>
                                        <select class="form-control select2" name="gender"
                                                id="gender">
                                            <option value="" disabled selected>Select gender
                                            </option>
                                            <option value="0"  >MALE
                                            </option>
                                            <option value="1"  >FEMALE
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>By Requested Date</label>

                                        <div class="input-daterange input-group" id="date-range">
                                            <input placeholder="dd/mm/yy" type="text" autocomplete="off"
                                                   class="form-control" value="" id="startDate" name="start"/>
                                            <input placeholder="dd/mm/yy" type="text" autocomplete="off"
                                                   class="form-control" value="" id="endDate" name="end"/>

                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="submit"
                                                class="btn form-control text-white btn-info waves-effect waves-light"
                                                style="margin-top: 21px;">Search
                                        </button>
                                    </div>

                                </div>
                            </form>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive b-0" data-pattern="priority-columns">
                                            <table class="table table-striped table-bordered"
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>NAME</th>
                                                    <th>ELECTION D.</th>
                                                    <th>POLLING B.</th>
                                                    <th>GRAMASEWA D.</th>
                                                    <th>VILLAGE</th>
                                                    <th>NIC</th>
                                                    <th>GENDER</th>
                                                    <th>CONTACT NO</th>
                                                    <TH>REQUESTED AT</TH>
                                                    <th>OPTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($users))
                                                    @if(count($users) > 0)
                                                        @foreach($users as $user)
                                                            <tr id="{{$user->idUser}}">
                                                                <td>{{strtoupper($user->userTitle->title)}} {{strtoupper($user->fName)}} {{strtoupper($user->lName)}}</td>
                                                                <td>{{strtoupper($user->agent->electionDivision->division_name)}}</td>
                                                                <td>{{strtoupper($user->agent->pollingBooth->booth_name)}}</td>
                                                                <td>{{strtoupper($user->agent->gramasewaDivision->gramasewa_name)}}</td>
                                                                <td>{{strtoupper($user->agent->village->village_name)}}</td>
                                                                <td>{{$user->nic}}</td>
                                                            @if($user->gender == 0)
                                                                    <td>MALE</td>
                                                                @elseif ($user->gender == 1)
                                                                    <td>FEMALE</td>
                                                                @else
                                                                    <td>FEMALE</td>
                                                                @endif
                                                                <td>{{$user->contact_no1}}</td>
                                                                <td>{{$user->created_at}}</td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                                                type="button" id="dropdownMenuButton"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false">
                                                                            Option
                                                                        </button>

                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a href="#" class="dropdown-item">View
                                                                            </a>
                                                                            <a href="#" onclick="approveAgent({{$user->idUser}})" class="dropdown-item">Approve
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
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->

@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {

        });

        function approveAgent(id) {
            swal({
                title: 'Do you want to approve this user?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                buttonsStyling: false
            }).then(function () {


//                $.post('removeGrnItemTable', {rid: rid}, function (data) {
//
//                    if (data == "1") {
//                        swal(
//                            'Deleted!',
//                            'Item deleted successfully.',
//                            'success'
//                        )
//                        loadGrnItemTempTable();
//                    } else {
//                        swal(
//                            'Failed!',
//                            'Invalid info or Invalid Item!',
//                            'error'
//                        )
//                    }
//
//
//                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Process has been cancelled',
                        'error'
                    )
                }
            })
        }
    </script>
@endsection