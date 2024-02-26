<?php

namespace App\Http\Controllers;

use App\ExcelExports\ExportExcelClass;
use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;

class CustomersReportController extends Controller
{
    public function index()
    {

        $sections = sections::all();
        return view('reports.customers_report', compact('sections'));

    }

    public function Search_customers(Request $request)
    {

        // في حالة البحث بدون التاريخ
        if ((empty($request->start_at && $request->end_at))) {
            $invoices = invoices::select('*')->where('section_id', '=', $request->Section)->where('product', '=', $request->product)->get();
            $sections = sections::all();
            $request->session()->put('invoices', $invoices);
            return view('reports.customers_report', compact('sections'))->with('details', $invoices);
        }

////////////////////////////////////////////////////////////////////////////////////

        // في حالة البحث بتاريخ
        elseif ((!empty($request->start_at && $request->end_at))) {

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            $invoices = invoices::whereBetween('invoice_Date', [$start_at, $end_at])->where('section_id', '=', $request->Section)->where('product', '=', $request->product)->get();
            $sections = sections::all();
            $request->session()->put('invoices', $invoices);
            return view('reports.customers_report', compact('sections'))->with('details', $invoices);
        }

    }
    public function ExportExcel(Request $request)
    {
        if ($request->session()->has('invoices')) {
            $invoices = $request->session()->get('invoices');
            $invoices_reports = new ExportExcelClass();
            $invoices_reports->InvoicesReport($invoices);
        } else {
            session()->flash('session_error', 'حدث خطأ نرجوا اعادة المحاولة!');
            return redirect()->back();
        }

    }
}