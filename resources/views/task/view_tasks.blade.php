@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card m-b-20">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <form action="{{route('viewTasks')}}" method="GET">
                                <div class="row">
                                    {{ csrf_field() }}


                                    <div class="form-group col-md-6 ">
                                        <label for="searchCol">Search By</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <select class="form-control  " name="searchCol"
                                                        id="searchCol" required>
                                                    <option value="1" selected>AGENT FIRST NAME</option>
                                                    <option value="1">AGENT LAST NAME</option>
                                                </select>
                                            </div>
                                            <input class="form-control " type="text" min="0" id="searchText"
                                                   name="searchText">

                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>By Created Date</label>

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
                                                style="margin-top: 28px;">Search
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
                                                    <th>AGENT NAME</th>
                                                    <th>VILLAGE</th>
                                                    <th># OF TARGETS</th>
                                                    <th>ACHIEVED</th>
                                                    <th>STATUS</th>
                                                    <th>CREATED AT</th>
                                                    <th>OPTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($tasks))
                                                    @if(count($tasks) > 0)
                                                        @foreach($tasks as $task)
                                                            <tr id="{{$task->idtask}}">
                                                                <td>{{strtoupper($task->user->userTitle->name_en)}}{{strtoupper($task->user->fNmae)}} {{strtoupper($task->user->lName)}} </td>
                                                                <td>{{strtoupper($task->user->agent->village->name_en)}} </td>
                                                                <td>{{strtoupper($task->target)}} </td>
                                                                <td>{{strtoupper($task->completed_amount)}} </td>
                                                                @if($task->status == 1)
                                                                    <td>
                                                                        <em style="color: #00a5bb"
                                                                            class="mdi mdi-checkbox-blank-circle"></em>
                                                                        PENDING
                                                                    </td>
                                                                @elseif($task->status == 2)
                                                                    <td>
                                                                        <em style="color: #0dbb2f"
                                                                            class="mdi mdi-checkbox-blank-circle"></em>
                                                                        COMPLETED
                                                                    </td>
                                                                @elseif($task->status == 0)
                                                                    <td>
                                                                        <em style="color: #bb2011"
                                                                            class="mdi mdi-checkbox-blank-circle"></em>
                                                                        DELETED
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <em style="color: #000405"
                                                                            class="mdi mdi-checkbox-blank-circle"></em>
                                                                        UNKNOWN
                                                                    </td>
                                                                @endif
                                                                <td>{{$task->created_at}} </td>

                                                                <td>
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
                                                                               onclick="viewTask({{$task->idtask}})"
                                                                               class="dropdown-item">View
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
                            @if(isset($offices))
                                {{$offices->links()}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->


    <!-- modal start -->

    <div class="modal fade" id="viewModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">View Task</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    

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

        function viewTask(id) {
            $.ajax({
                url: '{{route('getTaskById')}}',
                data: {id: id},
                type: 'POST',
                success: function (data) {
                    console.log(data.success);
                    $('#viewModal').modal('show');
                }
            });
        }

    </script>
@endsection