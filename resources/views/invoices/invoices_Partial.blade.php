@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('title')
    الفواتير المدفوعة جزئيا
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    الفواتير المدفوعة جزئيا</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if (session()->has('Error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('status_update'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('status_update') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    @if (session()->has('delete'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('transfer'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('transfer') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <a href="invoices/create" class="modal-effect btn btn-sm btn-primary" style="color:white"><i
                                class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-md-nowrap" id="example1">
                                <thead>
                                    <tr>
                                        <th class="wd-15p border-bottom-0">#</th>
                                        <th class="wd-15p border-bottom-0">رقم الفاتورة</th>
                                        <th class="wd-15p border-bottom-0">تاريخ الفاتورة</th>
                                        <th class="wd-15p border-bottom-0">تاريخ الاستحقاف</th>
                                        <th class="wd-15p border-bottom-0">المنتج</th>
                                        <th class="wd-15p border-bottom-0">القسم</th>
                                        <th class="wd-15p border-bottom-0">الخصم</th>
                                        <th class="wd-15p border-bottom-0">نسبة الضريبة</th>
                                        <th class="wd-15p border-bottom-0">قيمة الضريبة</th>
                                        <th class="wd-15p border-bottom-0">الاجمالي</th>
                                        <th class="wd-15p border-bottom-0">حالة الدفع</th>
                                        <th class="wd-15p border-bottom-0">الوصف</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    @foreach ($invoices as $invoice)
                                        <?php $i++; ?>
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->invoice_Date }}</td>
                                            <td>{{ $invoice->Due_date }}</td>
                                            <td>{{ $invoice->product }}</td>
                                            <td><a
                                                    href="{{ url('GetInvoicesDetails') }}{{ $invoice->id }}">{{ $invoice->section->section_name }}</a>
                                            </td>
                                            <td>{{ $invoice->Discount }}</td>
                                            <td>{{ $invoice->Rate_VAT }}</td>
                                            <td>{{ $invoice->Value_VAT }}</td>
                                            <td>{{ $invoice->Total }}</td>
                                            <td>
                                                @if ($invoice->Value_Status == 1)
                                                    <span class="text-success">{{ $invoice->Status }}</span>
                                                @elseif($invoice->Value_Status == 2)
                                                    <span class="text-danger">{{ $invoice->Status }}</span>
                                                @else
                                                    <span class="text-warning">{{ $invoice->Status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $invoice->note }}</td>
                                            <td>
                                                <!-- proccesses -->
                                                <div class="dropdown">
                                                    <button aria-expanded="true" aria-haspopup="true"
                                                        class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                        type="button">العمليات<i
                                                            class="fas fa-caret-down ml-1"></i></button>

                                                    <div class="dropdown-menu tx-13">

                                                        @can('تعديل الفاتورة')
                                                            <!-- edit invoice -->
                                                            <a class="dropdown-item"
                                                                href="{{ url('edit_invoices') }}/{{ $invoice->id }}"> <i
                                                                    class="text-danger fas far fa-edit"></i>&nbsp;&nbsp;تعديل
                                                                الفاتورة
                                                            </a>
                                                        @endcan

                                                        @can('حذف الفاتورة')
                                                            <!-- delete invoice -->
                                                            <a class="dropdown-item" href="#"
                                                                data-invoice_id="{{ $invoice->id }}" data-toggle="modal"
                                                                data-target="#delete_invoice"><i
                                                                    class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                                الفاتورة</a>
                                                        @endcan

                                                        @can('تغير حالة الدفع')
                                                            <!-- payment status change -->
                                                            <a class="dropdown-item"
                                                                href="{{ url('payment_status_show') }}/{{ $invoice->id }} }}"><i
                                                                    class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغيير
                                                                حالة الدفع</a>
                                                        @endcan

                                                        @can('ارشفة الفاتورة')
                                                            <!-- move invoice to archive -->
                                                            <a class="dropdown-item" href="#"
                                                                data-invoice_id="{{ $invoice->id }}" data-toggle="modal"
                                                                data-target="#Transfer_invoice"><i
                                                                    class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل
                                                                الي الارشيف
                                                            </a>
                                                        @endcan

                                                        @can('طباعةالفاتورة')
                                                            <!-- print invoice -->
                                                            <a class="dropdown-item"
                                                                href="print_invoice/{{ $invoice->id }}"><i
                                                                    class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                                الفاتورة
                                                            </a>
                                                        @endcan

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div>
    </div>
    <!-- /row -->

    <!-- delete invoice -->
    <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                </div>
                <div class="modal-body">
                    هل انت متاكد من عملية الحذف ؟
                    <input type="hidden" name="invoice_id" id="invoice_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-danger">تاكيد</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- transfer invoice -->
    <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">أرشفة الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                </div>
                <div class="modal-body">
                    هل انت متاكد من عملية الأرشفة ؟
                    <input type="hidden" name="invoice_id" id="invoice_id" value="">
                    <input type="hidden" name="id_page" id="id_page" value="2">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-success">تاكيد</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <!-- delete invoice -->
    <script>
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
    </script>

    <!-- transfer invoice -->
    <script>
        $('#Transfer_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
    </script>

@endsection
