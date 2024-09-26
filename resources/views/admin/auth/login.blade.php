<!doctype html>
<html lang="en">

<head>
  @include('admin.auth.head')
</head>

<body class="body_color">
<style>
    .ic_main_form_inner{
        border-radius: 46px;
        width: 470px;
        @media (max-width: 576px){
            width: 100%!important;
        }
    }
    .submit_btn{
        border-radius: 25px;
    }
    .ic_main_form_inner .form_box img {
        max-width: 280px;
        margin-bottom: 7px
    }
    .ic_main_form_area::before{
        background: white!important;
    }
    .img-fluid {
        max-width: 90%;
        height: auto;
    }
    .form-control{
        width: 82%;
        display: initial;
    }
    .main-logo{
        text-align: center;
    }
</style>
<!--================Login Form Area =================-->
<!-- Begin page -->

    <section class="ic_main_form_area" style="background: white">
        <div class="container">
            @include('flash::message')
            <div class="row align-items-center  mt-1">
                <div class="col-lg-6 m-auto ml-lg-auto">
                    <!-- <div class="card-body main-logo">
                        @if(config('site_logo'))
                            <img class="ic-login-img img-fluid" src="{{ config('site_logo') }}" alt="logo">
                        @else
                            <img class="img-fluid ic-login-img" src="{{ config('site_logo') ?? static_asset('admin/images/logo.png') }}" alt="imgs">

                        @endif
                    </div> -->
                    <div class="ic_main_form_inner mb-5">
                        <div class="form_box">
                            <div class="card-body main-logo">
                                @if(config('site_logo'))
                                    <img class="ic-login-img img-fluid" src="{{ config('site_logo') }}" alt="logo">
                                @else
                                    <img class="img-fluid ic-login-img" src="{{ config('site_logo') ?? static_asset('admin/images/logo.png') }}" alt="imgs">
                                @endif
                            </div>
                            <!-- <div class="col-lg-12">
                                <h4 style="color: #795000;font-weight: 600;font-size: 30px;">{{__('Child Rights Tracking Tools')}}</h4>
                                <h4 style="color: #002979;font-weight: 700;">{{__('custom.login')}}</h4>
                            </div> -->

                            @if(session()->has('loginFail'))
                            <p class="alert alert-danger text-center">
                                {{ session()->get('loginFail') }}
                            </p>
                            @endif


                            <form class="row login_form justify-content-center" action="{{ url('/admin/login') }}" method="post" id="loginForm" novalidate="novalidate">

                                @csrf

                                <div class="form-group col-lg-12  mt-4">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Username">
                                    <!-- <i class="fa fa-user"></i> -->
                                </div>
                                @if ($errors->has('email'))
                                <p class="ic-error-massage">{{ $errors->first('email') }}</p>
                                @endif
                                <div class="form-group col-lg-12">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    <!-- <i class="fa fa-unlock-alt" aria-hidden="true"></i> -->

                                </div>
                                <!-- @if ($errors->has('password'))
                                <p class="ic-error-massage">{{ $errors->first('password') }}</p>
                                @endif
                                <div class="form-group col-lg-10">
                                        @if(config('app.env') != 'demo')
                                        <a href="{{route('admin.auth.reset-password')}}" class="float-right text-black forgot-pass">
                                            <span class="text-forgot">Forgot Password ?</span>
                                        </a>
                                        @endif
                                </div> -->
                                <div class="form-group col-lg-12">
                                    <button type="submit" value="submit" class="btn submit_btn form-control">{{
                                        __('custom.login') }}</button>
                                </div>
                            </form>
                        </div>
                        <!-- <div class="text-center login-form-footer">© {{ date('Y') }} {{
                            __('custom.all_right_reserved') }} | {{ __('custom.design_and_developed') }} <a class="ic-main-color" href="https://beatniktechnology.com/"><span class="d-block">Beatnik Technology</span></a></div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
<!--================End Login Form Area =================-->

@include('admin.auth.script')

</body>

</html>
