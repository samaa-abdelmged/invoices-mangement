@extends('layouts.master')

@section('title')
    لوحة التحكم - برنامج الفواتير
@stop

@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
@endsection

<!-- page-header -->
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            @if (session()->has('became_active'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session()->get('became_active') }}</strong>
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
            <div>
                <h3 class="mg-b-0">لوحة التحكم - برنامج الفواتير</h3>
                <br>
                <h4 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">أهلا بعودتك!</h4>
                <br>
            </div>
        </div>
        <div class="main-dashboard-header-right">
        </div>
    </div>
    <!-- /breadcrumb -->
@endsection
@section('content')
    <!-- frist row -->

    <div class="row row-sm">
        <!-- كارت اجمالي الفواتير  -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">اجمالي الفواتير</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h3 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(\App\Models\invoices::sum('Total'), 2) }}</h3>
                                <br>
                                <h6 class="mb-3 tx-12 text-white">عدد الفواتير &nbsp; {{ \App\Models\invoices::count() }}
                                </h6>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                <i class="fas fa-arrow-circle text-white"></i>
                                <span class="mb-3 tx-12 text-white">100%</span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>

        <!-- كارت الفواتير الغير مدفوعة  -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">الفواتير الغير مدفوعة</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h3 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(\App\Models\invoices::where('Value_Status', 2)->sum('Total'), 2) }}
                                </h3>
                                <br>

                                <h6 class="mb-3 tx-12 text-white">عدد الفواتير
                                    &nbsp;{{ \App\Models\invoices::where('Value_Status', 2)->count() }}

                                </h6>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                <i class="fas fa-arrow-circle text-white"></i>
                                <span class="text-white op-7">
                                    @php
                                        $count_all = \App\Models\invoices::count();
                                        $count_invoices2 = \App\Models\invoices::where('Value_Status', 2)->count();

                                        if ($count_invoices2 == 0) {
                                            echo $count_invoices2 = 0, '%';
                                        } else {
                                            $count_invoices2 = ($count_invoices2 / $count_all) * 100;
                                            echo round($count_invoices2, 1, PHP_ROUND_HALF_UP), '%';
                                        }
                                    @endphp
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline2" class="pt-1">3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7</span>
            </div>
        </div>

        <!-- كارت الفواتير المدفوعة  -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">الفواتير المدفوعة</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(\App\Models\invoices::where('Value_Status', 1)->sum('Total'), 2) }}
                                </h4>
                                <br>
                                <p class="mb-3 tx-12 text-white">
                                    عدد الفواتير
                                    &nbsp;{{ \App\Models\invoices::where('Value_Status', 1)->count() }}
                                </p>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                <i class="fas fa-arrow-circle text-white"></i>
                                <span class="text-white op-7">
                                    @php
                                        $count_all = \App\Models\invoices::count();
                                        $count_invoices1 = \App\Models\invoices::where('Value_Status', 1)->count();

                                        if ($count_invoices1 == 0) {
                                            echo $count_invoices1 = 0, '%';
                                        } else {
                                            $count_invoices1 = ($count_invoices1 / $count_all) * 100;
                                            echo round($count_invoices1, 1, PHP_ROUND_HALF_UP), '%';
                                        }
                                    @endphp</span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline3" class="pt-1">5,10,5,20,22,12,15,18,20,15,8,12,22,5,10,12,22,15,16,10</span>
            </div>
        </div>

        <!--  كارت الفواتير المدفوعة جزئيا  -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">الفواتير المدفوعة جزئيا</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(\App\Models\invoices::where('Value_Status', 3)->sum('Total'), 2) }}
                                </h4>
                                <br>
                                <p class="mb-3 tx-12 text-white">
                                    عدد الفواتير
                                    &nbsp;{{ \App\Models\invoices::where('Value_Status', 3)->count() }}
                                </p>
                            </div>
                            <span class="float-right my-auto mr-auto">
                                <i class="fas fa-arrow-circle text-white"></i>
                                <span class="text-white op-7">
                                    @php
                                        $count_all = \App\Models\invoices::count();
                                        $count_invoices1 = \App\Models\invoices::where('Value_Status', 3)->count();

                                        if ($count_invoices1 == 0) {
                                            echo $count_invoices1 = 0, '%';
                                        } else {
                                            $count_invoices1 = ($count_invoices1 / $count_all) * 100;
                                            echo round($count_invoices1, 1, PHP_ROUND_HALF_UP), '%';
                                        }
                                    @endphp
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>

    </div>
    <!-- row closed -->


    <!-- row opened -->
    <div class="row row-sm">


        <span style="padding: 30px;"></span>

        <!-- نسبة احصائيات الفواتير -->
        <div class="col-lg-12 col-xl-5">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title mb-0">نسبة أعداد الفواتير</h3>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                </div>
                <div class="card-body" style="width: 75%">
                    {!! $chartjs->render() !!}
                </div>
            </div>
        </div>

        <span style="padding: 30px;"></span>
        <!-- نسبة احصائيات الفواتير -->
        <div class="col-lg-12 col-xl-5">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title mb-0">النسبة المئوية لقيمة الفواتير</h3>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                </div>
                <div class="card-body" style="width: 75%">
                    {!! $chartjs_2->render() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->

    </div>

    </div>
    </div>
    </div>
    <!-- Container closed -->

@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Moment js -->
    <script src="{{ URL::asset('assets/plugins/raphael/raphael.min.js') }}"></script>
    <!--Internal  Flot js-->
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ URL::asset('assets/js/dashboard.sampledata.js') }}"></script>
    <script src="{{ URL::asset('assets/js/chart.flot.sampledata.js') }}"></script>
    <!--Internal Apexchart js-->
    <script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
    <!-- Internal Map -->
    <script src="{{ URL::asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal-popup.js') }}"></script>
    <!--Internal  index js -->
    <script src="{{ URL::asset('assets/js/index.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.vmap.sampledata.js') }}"></script>
@endsection
