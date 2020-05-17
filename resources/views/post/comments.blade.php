@extends('layouts.main')
@section('psStyle')
    <style>

        .commentsContainer{
            border: solid 1px black;
            border-radius: 10px;
            height: 100Vh;
            overflow: scroll;
            /*background: rgba(181, 189, 192, 0.55);*/
          }

        .commentersContainer{
            border: solid 1px black;
            position:relative;

            background: rgb(27,66,190);
            background: linear-gradient(90deg, rgba(27,66,190,0.5533123230666257) 0%, rgba(0,212,255,0.6628628848643785) 100%);

            border-radius: 10px;
            height: 100Vh;
            overflow: scroll;
        }

        .commenterBox{
            background-color: rgba(58, 80, 96, 0.61);
        }
        .commenterBox.active{
            background-color: rgba(140, 164, 174, 0.61);
        }

        .receivedComment{
            border-radius: 10px;
            background-color: #82CCDD;

        }

        .ownComment{
            border-radius: 10px;
            background-color: #78E08F;
        }

        .writingSection{
            height: 30px;
            border: solid 1px black;
            border-radius: 10px;
        }

        .bottom{
            position:absolute;
            bottom:0;
            width: 100%}

        .mediaIcon{
            //
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

                                <div class="col-md-3 my-2 commentersContainer">
                                    @if(isset($commenters))
                                        @if(count($commenters) > 0)
                                            @for($i=0 ; $i <3;$i++)
                                            @foreach($commenters as $key=>$item)
                                                <div class="row  commenterBox m-2 p-5">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                               {{$item[0]->user->fName}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endforeach
                                                @endfor
                                        @else
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-9  my-2  commentsContainer">
                                    <div class="row">

                                        <div class="col-md-10 col-sm-8 col-sm-8 ownComment p-2 m-2 ml-auto ">df</div>
                                        <div class="col-md-10 col-sm-8 col-sm-8 receivedComment  m-2 p-2 ">df</div>

                                        <div id="previewCard"></div>
                                    </div>
                                    <div class="row bottom">
                                        <div class="col-md-12">
                                            <textarea class="form-control" id="comment" rows="1"></textarea>
                                        </div>
                                        <div class="col-md-12">.
                                            <input type="file" style="display: none" id="imageFiles" onchange="readURL(this,1)"
                                                   name="imageFiles[]" multiple accept="image/*">
                                            <input type="file" style="display: none" id="videoFiles" onchange="readURL(this,2)"
                                                   name="videoFiles[]" multiple
                                                   accept="video/*">
                                            <input type="file" style="display: none" id="audioFiles" name="audioFiles[]"
                                                   onchange="readURL(this,3)"
                                                   multiple
                                                   accept="audio/*">
                                            <em onclick="$('#imageFiles').click()" class="text-success mediaIcon fa fa-image (alias) fa-3x mr-2"></em>
                                            <em onclick="$('#videoFiles').click()" class="text-success mediaIcon fa fa-video-camera fa-3x mx-2"></em>
                                            <em onclick="$('#audioFiles').click()" class="text-success mediaIcon fa fa-microphone fa-3x mx-2"></em>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <br/>

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
        function readURL(input, type) {
            if (input.files) {
                var filesAmount = input.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function (event) {
                        if (type == 1) {
                            $('#previewCard').append("<div class='col-md-3 py-2 text-center'><img alt='image preview' class='attachmentPreview' src='" + event.target.result + "'></div>");

                        }
                        else if (type == 2) {
                            $('#previewCard').append("<div class='col-md-3 py-2 text-center'><div class='bg-info  audioVideoPreview'><em style='width: 100%' class='center fa fa-file-movie-o (alias) fa-3x text-white'></em></div></div>");

                        }
                        else if (type == 3) {
                            $('#previewCard').append("<div class='col-md-3 py-2 text-center'><div class='bg-info  audioVideoPreview'><em style='width: 100%' class='center fa  fa-file-audio-o fa-3x text-white'></em></div></div>");

                        }
                    }

                    reader.readAsDataURL(input.files[i]);
                }
            }
        }
    </script>
@endsection