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


                                    <div class="form-group col-md-4 ">
                                        <label for="category">Category Name</label>
                                        <input class="form-control " type="text" placeholder="Search category name here" id="category"
                                               name="category">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="subCat">By Sub Category</label>

                                        <select class="form-control select2" name="subCat"
                                                id="subCat">
                                            <option value="" disabled selected>Select sub category
                                            </option>
                                            @if($subCategories != null)
                                                @foreach($subCategories as $subCategory)
                                                    <option value="{{$subCategory->idsub_caategory}}">{{$subCategory->category}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="mainCat">By Main Category</label>

                                        <select class="form-control select2" name="mainCat"
                                                id="mainCat">
                                            <option value="" disabled selected>Select main category
                                            </option>
                                            @if($mainCategories != null)
                                                @foreach($mainCategories as $mainCategory)
                                                    <option value="{{$mainCategory->idmain_caategory}}">{{$mainCategory->category}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
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
                                                    <th>CATEGORY</th>
                                                    <th>SUB CATEGORY</th>
                                                    <th>MAIN CATEGORY</th>
                                                    <th>CREATED AT</th>
                                                    <th>OPTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($categories))
                                                    @if(count($categories) > 0)
                                                        @foreach($categories as $category)
                                                            <tr id="{{$category->idcategory}}">
                                                                <td>{{strtoupper($category->category)}} </td>
                                                                <td>{{strtoupper($category->subCategory->categroy)}} </td>
                                                                <td>{{strtoupper($category->subCategory->mainCategory->category)}} </td>
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
                                                                            <a href="#" class="dropdown-item">Edit
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
                            @if(isset($categories))
                                {{$categories->links()}}
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

    </script>
@endsection