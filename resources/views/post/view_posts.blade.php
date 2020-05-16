@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <h4>In development. . .</h4>
            @if(1==2)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12">

                                    @if(isset($posts))
                                        @if(count($posts) > 0)
                                            @foreach($posts as $post)
                                                <div style="border-radius: 10px;" class="row bg-secondary m-2 p-5">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h4>{{$post->title_en}}</h4>
                                                                <small class="float-right"> - {{$post->user->office->office_name}}</small>
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                            <div class="col-md-2"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--@if($post->attachments != null)--}}
                                                    {{--@foreach($post->attachments as $postAttachment)--}}
                                                        {{--@if($postAttachment->file_type == 1)--}}
                                                            {{--<img src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->getFile())}}">--}}
                                                        {{--@elseif ($postAttachment->file_type == 2)--}}
                                                            {{--<video width="400" controls>--}}
                                                                {{--<source src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->getFile())}}"--}}
                                                                        {{--type="video/mp4">--}}
                                                                {{--<source src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->getFile())}}"--}}
                                                                        {{--type="video/ogg">--}}
                                                                {{--Your browser does not support HTML video.--}}
                                                            {{--</video>--}}
                                                        {{--@elseif ($postAttachment->file_type == 3)--}}
                                                            {{--<audio controls>--}}
                                                                {{--<source src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->getFile())}}"--}}
                                                                        {{--type="audio/ogg">--}}
                                                                {{--<source src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->getFile())}}"--}}
                                                                        {{--type="audio/mpeg">--}}
                                                                {{--Your browser does not support the audio element.--}}
                                                            {{--</audio>--}}
                                                        {{--@endif--}}
                                                    {{--@endforeach--}}
                                                {{--@endif--}}

                                            @endforeach
                                        @else
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endif

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {

        });

    </script>
@endsection