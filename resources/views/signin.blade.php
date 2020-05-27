@include('includes.header_start')
@include('includes.common_styles')
<!--sign in page labels -->
<link href="{{ URL::asset('assets/css/bootstrap-extended.min.css')}}" rel="stylesheet" type="text/css">

<!-- Begin page -->
<div class="wrapper-page" >

    <div class="card " style="background-color: rgba(71,184,210,0.27)">
        <div class="card-body">


            <div class="row d-flex justify-content-center">
                <div  class="col-xl-6 col-lg-6 col-md-12 col-xm-12 col-xs-12">
                    <div class="text-center m-0">
                        <img  style="border-radius: 20px;" draggable="false" src="{{\Illuminate\Support\Facades\URL::asset('assets/images/resources/login3.jpg')}}" width="100%"
                                                                     alt="Lock">
                    </div>

                </div>

                <div  class="col-xl-6 col-lg-6 col-md-12 col-xm-12 col-xs-12">


                    <div class="p-3">
                        <h4 class="text-muted font-18 m-b-5 text-center">{{ __('Login_title') }}</h4>
                        <p class="text-muted text-center">{{ __('Login_text') }}</p>

                        @if(\Session::has('error'))
                            <div style="background-color: #beece7" class="alert alert-danger alert-dismissible ">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p>{{ \Session::get('error') }}</p>
                            </div>
                        @endif

                        @if(\Session::has('warning'))
                            <div style="background-color: #C6E5EC" class="alert alert-warning alert-dismissible ">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p>{{ \Session::get('warning') }}</p>
                            </div>
                        @endif


                        <form class="form-horizontal m-t-30" action="{{ route('login') }}" method="POST">
                            {{--<form class="form-horizontal m-t-30" action="{{ route('authenticate') }}" method="POST">--}}





                            <fieldset class="form-label-group position-relative has-icon-left">

                                <input type="text" class="form-control" id="username" name="username" autocomplete="off"
                                       placeholder="Enter username">
                                <div class="form-control-position">
                                    <i class="feather icon-lock"></i>
                                </div>
                                <label for="username">User Name</label>
                                <small class="text-danger">{{ $errors->first('username') }}</small>
                            </fieldset>

                            <fieldset class="form-label-group position-relative has-icon-left">

                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Enter password">
                                <div class="form-control-position">
                                    <i class="feather icon-lock"></i>
                                </div>
                                <label for="pass">Password</label>
                                <small class="text-danger">{{ $errors->first('password') }}</small>
                            </fieldset>
                            <input type="hidden" name="_token" value="{{ Session::token() }}">

                            <div class="form-group row m-t-20">
                                <div class="col-sm-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="rememberme"
                                               id="customControlInline">
                                        <label class="custom-control-label" for="customControlInline">Remember me</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Log In</button>
                                </div>
                            </div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">
                                    <a href="pages-recoverpw" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your
                                        password?</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>




        </div>
    </div>

    <div class="m-t-40 text-center">
        <p> © <?php echo date('Y') ?> Developed By <img
                    style="padding-bottom: 3px;height: 20px;"
                    src="{{ URL::asset('assets/images/resources/logo.svg') }}"/></p>

    </div>

</div>

@include('includes.common_scripts')
@include('includes.footer_end')