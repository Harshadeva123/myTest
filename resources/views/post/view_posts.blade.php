@extends('layouts.main')
@section('psStyle')
    <style>

        .postContainer {
            border: solid 1px #b9b9b9;
            border-radius: 10px;
            /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#ebf1f6+0,abd3ee+50,89c3eb+51,d5ebfb+100;Blue+Gloss+%234 */
            background: #ebf1f6; /* Old browsers */
            background: -moz-linear-gradient(-45deg,  #ebf1f6 0%, #abd3ee 50%, #89c3eb 51%, #d5ebfb 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(-45deg,  #ebf1f6 0%,#abd3ee 50%,#89c3eb 51%,#d5ebfb 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(135deg,  #ebf1f6 0%,#abd3ee 50%,#89c3eb 51%,#d5ebfb 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ebf1f6', endColorstr='#d5ebfb',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */

        }

        .postImage {
            /*border: solid 3px #cacaca;*/
            /*border-radius: 10px;*/
        }
    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12">

                                    @if(isset($posts))
                                        @if(count($posts) > 0)
                                            @foreach($posts as $post)
                                                <form action="{{route('viewComments')}}" id="form-{{$post->idPost}}"
                                                      method="POST">
                                                    {{csrf_field()}}
                                                    <div onclick="showPostContent({{$post->idPost}})"
                                                         class="row postContainer m-2 p-3">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h4>{{$post->title_en}}</h4>
                                                                    <div class="row">

                                                                        <small title="storage" class="float-left col-md-3">
                                                                            <em class="fa fa-database text-muted"></em> {{round($post->getSize() / 1000000,2)}} MB
                                                                        </small>
                                                                        <input type="hidden" name="post_no"
                                                                               value="{{$post->post_no}}">
                                                                        @if($post->responses()->count() >0)
                                                                            <p onclick="event.stopPropagation();"
                                                                                   class="float-left col-md-3">
                                                                                <a href="#"
                                                                                   onclick="$('#form-{{$post->idPost}}').submit()">Comments</a>&nbsp;<span
                                                                                        class="badge badge-info">{{$post->responses()->count()}}</span>&nbsp
                                                                            </p>
                                                                        @else
                                                                            <p onclick="event.stopPropagation();"
                                                                                   class="float-left col-md-3">
                                                                                <a href="#"
                                                                                   onclick="$('#form-{{$post->idPost}}').submit()">Comments</a>&nbsp;<span
                                                                                        class="badge badge-secondary">{{$post->responses()->count()}}</span>&nbsp
                                                                            </p>
                                                                        @endif
                                                                        <small title="author" style="text-align: center"  class="float-right col-md-3">
                                                                            - {{$post->user->office->office_name}}
                                                                        </small>
                                                                        <small title="created at" style="text-align: right;" class="float-right col-md-3">
                                                                            {{$post->created_at}}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                                {{--@if($post->attachments != null)--}}
                                                {{--@foreach($post->attachments as $postAttachment)--}}
                                                {{--@if($postAttachment->file_type == 1)--}}
                                                {{--<img src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->full_path)}}">--}}
                                                {{--@elseif ($postAttachment->file_type == 2)--}}
                                                {{--<video width="400" controls>--}}
                                                {{--<source src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->full_path)}}"--}}
                                                {{--type="video/mp4">--}}
                                                {{--<source src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->full_path)}}"--}}
                                                {{--type="video/ogg">--}}
                                                {{--Your browser does not support HTML video.--}}
                                                {{--</video>--}}
                                                {{--@elseif ($postAttachment->file_type == 3)--}}
                                                {{--<audio controls>--}}
                                                {{--<source src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->full_path)}}"--}}
                                                {{--type="audio/ogg">--}}
                                                {{--<source src="{{ \Illuminate\Support\Facades\URL::asset($postAttachment->full_path)}}"--}}
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

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->

    <!-- modal start -->

    <div class="modal fade" id="viewPost" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">View Post</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-secondary" id="modelTitle"></h4>
                        </div>
                    </div>
                    {{--<h6 class="text-secondary">Post Text</h6>--}}
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-secondary" id="modelEnglish"></h6>
                        </div>
                    </div>
                    {{--<h6 class="text-secondary">Post Attachments</h6>--}}
                    <hr/>
                    <div class="row">
                        <div class="col-md-12" id="modalAttachments"></div>

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
            initializeDate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function showPostContent(id) {
            $.ajax({
                url: '{{route('viewPostAdmin')}}',
                type: 'POST',
                data: {id: id},
                success: function (data) {
                    let postAttachments = data.success.attachments;
                    let postTextEnglish = data.success.text_en;
                    let postTitleEnglish = data.success.title_en;

                    let modalEnglish = $('#modelEnglish');
                    let modalAttachment = $('#modalAttachments');
                    let modalTitle = $('#modelTitle');
                    modalAttachment.html('');
                    modalEnglish.html('');

                    //post title
                    modalTitle.html(postTitleEnglish);
                    //post title end

                    // post text
                    modalEnglish.html(postTextEnglish);
                    //post text end

                    //post attachments
                    $.each(postAttachments, function (key, value) {
                        if (value.file_type == 1) {
                            modalAttachment.append("<img class='rounded postImage m-2' src='" + value.full_path + "' height='200px' width='200px'>");
                        } else if (value.file_type == 2) {
                            modalAttachment.append("" +
                                "<video width='400' controls>" +
                                "<source src='" + value.full_path + "' type='video/mp4'>" +
                                "<source src='" + value.full_path + "' type='video/ogg'> " +
                                "Your browser does not support HTML video." +
                                "</video>" +
                                "");
                        }
                        else if (value.file_type == 3) {
                            modalAttachment.append("" +
                                "<audio width='400' controls>" +
                                "<source src='" + value.full_path + "' type='audio/ogg'>" +
                                "<source src='" + value.full_path + "' type='audio/mpeg'> " +
                                "Your browser does not support the audio element." +
                                "</audio>" +
                                "");
                        }
                        //post attachments end
                    });
                    $('#viewPost').modal('show');
                }
            });
        }
    </script>
@endsection