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
                            <form action="{{route('viewCategory')}}" method="GET">
                                <div class="row">
                                    {{ csrf_field() }}


                                    <div class="form-group col-md-6 ">
                                        <label for="category">Category Name</label>
                                        <input class="form-control " type="text" placeholder="Search category name here"
                                               id="category"
                                               name="category">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="mainCat">By Main Category</label>

                                        <select class="form-control select2" name="mainCat"
                                                onchange="mainCategoryChanged(this.value,'subCat')"
                                                id="mainCat">
                                            <option value="" disabled selected>Select main category
                                            </option>
                                            @if($mainCategories != null)
                                                @foreach($mainCategories as $mainCategory)
                                                    <option value="{{$mainCategory->idmain_category}}">{{$mainCategory->category}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="subCat" class="control-label">{{ __('Sub Category') }}</label>
                                        <select id="subCat" name="subCat" class="form-control">
                                            <option value="" disabled selected>Select Sub Category</option>
                                        </select>
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
                                                    <th>CATEGORY</th>
                                                    <th>SUB CATEGORY</th>
                                                    <th>MAIN CATEGORY</th>
                                                    <th>STATUS</th>
                                                    <th>CREATED AT</th>
                                                    <th>OPTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($categories))
                                                    @if(count($categories) > 0)
                                                        @foreach($categories as $category)
                                                            <tr data-sub="{{$category->idsub_category}}" data-main="{{$category->subCategory->idmain_category}}" id="{{$category->idcategory}}">
                                                                <td>{{strtoupper($category->category)}} </td>
                                                                <td>{{strtoupper($category->subCategory->categroy)}} </td>
                                                                <td>{{strtoupper($category->subCategory->mainCategory->category)}} </td>
                                                                @if($category->status)
                                                                    <td nowrap><p><em  class="mdi mdi-checkbox-blank-circle text-success "></em> ACTIVE</p></td>
                                                                @else
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-danger"></em> DEACTIVATED</td>
                                                                @endif
                                                                <td>{{$category->created_at}}</td>
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
                                                                               onclick="showUpdateModal({{$category->idcategory}})"
                                                                               class="dropdown-item">Edit
                                                                            </a>
                                                                            @if($category->status == 1)
                                                                                <a href="#"
                                                                                   onclick="deactivateCategory({{$category->idcategory}})"
                                                                                   class="dropdown-item">Deactivate
                                                                                </a>
                                                                            @else
                                                                                <a href="#"
                                                                                   onclick="activateCategory({{$category->idcategory}})"
                                                                                   class="dropdown-item">Activate
                                                                                </a>
                                                                            @endif
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
                            @if(isset($categories))
                                {{$categories->links()}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->


    <!-- modal start -->
    <div class="modal fade" id="updateModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Update Office</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form1" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlertUpdate" style="display:none">
                                </div>
                            </div>
                        </div>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label for="mainCatU" class="control-label">{{ __('Main Category') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-1"></em></span>
                                    </div>
                                    <select id="mainCatU" name="mainCatU" class="form-control"
                                            onchange="setCustomValidity('');mainCategoryChanged(this.value,'subCatU')"
                                            oninvalid="this.setCustomValidity('Please select main category')"
                                            required>
                                        <option value="" disabled selected>Select Main Category</option>
                                        @if($mainCats != null)
                                            @foreach($mainCats as $mainCat)
                                                <option value="{{$mainCat->idmain_category}}">{{$mainCat->category}}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" class="noClear" id="updateId" name="updateId">
                        <div class="form-group col-md-6">
                            <label for="subCatU" class="control-label">{{ __('Sub Category') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-2"></em></span>
                                    </div>
                                    <select id="subCatU" name="subCat" class="form-control"
                                            onchange="setCustomValidity('')"
                                            oninvalid="this.setCustomValidity('Please select sub category')"
                                            required>
                                        <option value="" disabled selected>Select Sub Category</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="newCatU">{{ __('New Category') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="off" type="text" class="form-control" onchange="setCustomValidity('')"
                                           required  oninvalid="this.setCustomValidity('Please Enter category name')"
                                           placeholder="Enter category name here" name="newCat" id="newCatU">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group  col-md-3">
                            <button type="submit"
                                    class="btn btn-primary btn-block ">{{ __('Update Category') }}</button>
                        </div>
                        <div class="form-group  col-md-2">
                            <button type="submit" onclick="clearAll();event.preventDefault();"
                                    class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                        </div>
                    </div>
                    </form>
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

        function showUpdateModal(id) {
            $('#updateId').val(id);
            $('#mainCatU').val($('#'+id).attr('data-main')).trigger('change');
            setTimeout(function(){ $('#subCatU').val($('#'+id).attr('data-sub')).trigger('change'); }, 500);
            $('#newCatU').val($('#'+id).find("td").eq(0).html());

            $('#updateModal').modal('show');
        }

        function mainCategoryChanged(id, el) {
            if (id) {
                $('.notify').empty();
                $('.alert').hide();
                $('.alert').html("");
                $.ajax({
                    url: '{{route('getSubCatByMain')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlertU').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlertU').append('<p>' + value + '</p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            let array = data.success;
                            $('#' + el).html("<option value=''>Select Sub Category</option>");
                            $.each(array, function (index, value) {
                                $('#' + el).append(new Option(value.categroy, value.idsub_category));
                            });
                        }
                    }
                });
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
                    url: '{{route('updateCategory')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlertUpdate').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlertUpdate').append('<p>' + value + '</p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'CATEGORY UPDATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Category details updated successfully.'
                            });
                            location.reload();
                        }
                    }


                });
            }
            else {
                $('#errorAlert').html('Please provide all required fields.');
                $('#errorAlert').show();
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
            }
        });

        function deactivateCategory(id) {
            swal({
                title: 'Confirm?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Deactivate!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('deactivateCategory')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'DEACTIVATE PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 5000, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Something wrong with process.contact administrator..'
                            });
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'CATEGORY DEACTIVATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Category deactivated successfully.'
                            });
                            location.reload();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }

        function activateCategory(id) {
            swal({
                title: 'Confirm?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Activate!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('activateCategory')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'ACTIVATE PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 5000, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Something wrong with process.contact administrator..'
                            });
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'CATEGORY ACTIVATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Category activated successfully.'
                            });
                            location.reload();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }
    </script>
@endsection