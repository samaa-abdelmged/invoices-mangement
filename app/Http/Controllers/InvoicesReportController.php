<?php

namespace App\Http\Controllers;

use App\ExcelExports\ExportExcelClass;
use App\Models\invoices;
use Illuminate\Http\Request;

class InvoicesReportController extends Controller
{

    public function index()
    {
        return view('reports.invoices_report');

    }

    public function Search_invoices(Request $request)
    {
        $rdio = $request->rdio;

        // في حالة البحث بنوع الفاتورة

        if ($rdio == 1) {
            // في حالة عدم تحديد نوع الفاتورة
            if (empty($request->type)) {
                session()->flash('invoice_type', "قم بأختيار نوع الفاتورة");
                return view('reports.invoices_report');
            }
            // في حالة عدم تحديد تاريخ
            elseif ($request->start_at == '' || $request->end_at == '') {
                if ($request->type == "كل الفواتير") {
                    $invoices = invoices::select('*')->get();
                } else {
                    $invoices = invoices::select('*')->where('Status', '=', $request->type)->get();
                }
                $type = $request->type;
                $request->session()->put('invoices', $invoices);
                return view('reports.invoices_report', compact('type'))->with('details', $invoices);
            }

            // في حالة تحديد تاريخ استحقاق
            else {

                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $type = $request->type;
                if ($request->type == "كل الفواتير") {
                    $invoices = invoices::whereBetween('invoice_Date', [$start_at, $end_at])->get();
                } else {
                    $invoices = invoices::whereBetween('invoice_Date', [$start_at, $end_at])->where('Status', '=', $request->type)->get();

                }
                $request->session()->put('invoices', $invoices);
                return view('reports.invoices_report', compact('type', 'start_at', 'end_at'))->with('details', $invoices);

            }

        }

//====================================================================

// في حالة البحث برقم الفاتورة
        else {
            if (empty($request->invoice_number)) {
                session()->flash('invoice_number', "قم بكتابة رقم الفاتورة");
                return view('reports.invoices_report');
            }

            $invoices = invoices::select('*')->where('invoice_number', '=', $request->invoice_number)->get();
            $request->session()->put('invoices', $invoices);
            return view('reports.invoices_report')->with('details', $invoices);

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