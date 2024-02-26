@extends('layouts.master2')

@section('title')
    كود التحقق - برنامج الفواتير
@stop

@section('css')
    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
        rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session()->get('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session()->has('check'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session()->get('check') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session()->get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session()->has('active'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session()->get('active') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="container p-0">
                <div class="row">
                    <div class="your-container">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="bx bx-log-out"
                                style="position: absolutem;font-size: 20px; color: rgb(0, 204, 255);">تسجيل
                                خروج</i>
                        </a>
                    </div>
                    <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                        <div class="card-sigin">
                            <br>
                            <div class="mb-5 d-flex"><img src="{{ URL::asset('assets/img/brand/sky.png') }}"
                                    class="sign-favicon ht-40" alt="logo"></a>
                                <h1 class="main-logo1 ml-1 mr-0 my-auto tx-28">Soft<span>Ware</span> Sky</h1>
                            </div>
                            <div class="card-sigin">
                                <div class="main-signup-header">
                                    <h2>مرحبا بك</h2>
                                    <h5 class="font-weight-semibold mb-4">لقد تم ارسال كود التحقق الي البريد الالكتروني
                                        الخاص
                                        بك يرجي ادخال الكود هنا قبل انتهاء مدة تفعيلة</h5>

                                    <form action="{{ route('verify.store') }}" method="post">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label>كود التحقق</label>
                                            <br>
                                            <input id="code" class="form-control" name="code" required>
                                        </div>
                                        <button type="submit" class="btn btn-main-primary btn-block">
                                            {{ __('تحقق') }}
                                        </button>
                                    </form>

                                    <form action="{{ route('update_verify') }}" method="post">
                                        {{ csrf_field() }}

                                        <button type="submit" class="btn btn-danger"
                                            style="position: absolute; top: 130%; left: 50%; transform: translate(-50%, -50%);">
                                            {{ __('ارسال كود التحقق مرة اخري') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
