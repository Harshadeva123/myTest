@extends('layouts.main')
@section('psStyle')
    <style>

        .commentsContainer {
            border: solid 1px black;
            border-radius: 10px;
            height: 100Vh;
            overflow: scroll;
            /*background: rgba(181, 189, 192, 0.55);*/
        }

        .commentersContainer {
            border: solid 1px black;
            position: relative;

            background: rgb(27, 66, 190);
            background: linear-gradient(90deg, rgba(27, 66, 190, 0.5533123230666257) 0%, rgba(0, 212, 255, 0.6628628848643785) 100%);

            border-radius: 10px;
            height: 100Vh;
            overflow: scroll;
        }

        .commenterBox {
            background-color: rgba(58, 80, 96, 0.61);
            overflow-y: scroll;
            overflow-x: hidden;
            height: 85Vh;
        }

        /* width */
        .commenterBox::-webkit-scrollbar {
            width: 2px;
        }

        /* Track */
        .commenterBox::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        .commenterBox::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        .commenterBox::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Hide scrollbar for IE and Edge */
        .commenterBox {
            -ms-overflow-style: none;
        }

        .receivedComment {
            border-radius: 10px;
            background-color: #82CCDD;

        }

        .ownComment {
            border-radius: 10px;
            background-color: #78E08F;
        }

        .writingSection {
            height: 30px;
            border: solid 1px black;
            border-radius: 10px;
        }

        .bottom {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: rgba(58, 80, 96, 0.61);
        }

        .mediaIcon {
        / /
        }
    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body ">
                            <div class="row ">
                                <div class="col-md-12 commenterBox ">
                                    @if(isset($commenters))
                                        @if(count($commenters) > 0)
                                            @for($i=0 ; $i <12;$i++)
                                                @foreach($commenters as $key=>$item)
                                                    <div class="row">
                                                        @if($item->is_admin == 1)
                                                            <div class="ownComment col-md-8 p-2 m-2 ml-auto ">{{$item->response}}
                                                            </div>
                                                        @else
                                                            <div class="receivedComment  col-md-8 m-2 p-2 ">{{$item->response}}</div>
                                                        @endif
                                                    </div>

                                                @endforeach
                                            @endfor
                                        @else
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="row ">
                                <textarea title="Write your message here" class="form-control col-md-12" id="comment"
                                          rows="3"></textarea>
                                <div class="col-md-12">
                                    <div class="row my-1">
                                        <input class="" type="file" style="display: none" id="imageFiles"
                                               onchange="readURL(this,1)"
                                               name="imageFiles[]" multiple accept="image/*">
                                        <input type="file" style="display: none" id="videoFiles"
                                               onchange="readURL(this,2)"
                                               name="videoFiles[]" multiple
                                               accept="video/*">
                                        <input type="file" style="display: none" id="audioFiles" name="audioFiles[]"
                                               onchange="readURL(this,3)"
                                               multiple
                                               accept="audio/*">
                                        <em onclick="$('#imageFiles').click()"
                                            class="text-success mediaIcon fa fa-image (alias) fa-3x mr-2"></em>
                                        <em onclick="$('#videoFiles').click()"
                                            class="text-success mediaIcon fa fa-video-camera fa-3x mx-2"></em>
                                        <em onclick="$('#audioFiles').click()"
                                            class="text-success mediaIcon fa fa-microphone fa-3x mx-2"></em>
                                        <button class="btn btn-primary ml-auto  float-right">Send</button>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <br/>
                </div>
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

        });

        $("#formAttachments").on("submit", function (event) {
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
                    url: '{{route('saveCommentAttachments')}}',
                    type: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        console.log(data);
                        if (data.errors != null) {
                            $('#errorAlert3').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert3').append("<p><em class='fa fa-genderless'></em> " + value + "</p>");
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'POST PUBLISHED SUCCESSFULLY.',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Post published successfully.'
                            });
//                            clearAll();
                        }
                    }


                });
            }
            else {
                $('#errorAlert').html("<em class='fa fa-genderless'></em> Please provide all required fields.");
                $('#errorAlert').show();
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
            }
        });

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
                    url: '{{route('saveComment')}}',
                    type: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        console.log(data);
                        if (data.errors != null) {
                            $('#errorAlert3').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert3').append("<p><em class='fa fa-genderless'></em> " + value + "</p>");
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'POST PUBLISHED SUCCESSFULLY.',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Post published successfully.'
                            });
//                            clearAll();
                        }
                    }


                });
            }
            else {
                $('#errorAlert').html("<em class='fa fa-genderless'></em> Please provide all required fields.");
                $('#errorAlert').show();
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
            }
        });

    </script>
@endsection