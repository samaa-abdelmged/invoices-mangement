<?php

namespace App\Http\Controllers;

use App\ExcelExports\ExportExcelClass;
use App\Models\invoices;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use App\Models\sections;
use App\Models\User;
use App\Notifications\AddInvoiceCompleted;
use App\Notifications\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoices = invoices::all();
        $request->session()->put('invoices', $invoices);
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = sections::all();
        return view('invoices.add_invoices', compact('sections'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoices_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        //send mail
        $user = User::first();
        $user_name = $user->name;
        Notification::send($user, new SendMail($invoice_id, $user_name));

        // send notifications
        $user = User::get();
        $invoices = invoices::latest()->first();
        Notification::send($user, new AddInvoiceCompleted($invoices));

        // add fladh
        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {$invoices = invoices::where('id', $id)->first();
        $sections = sections::all();
        return view('invoices.edit_invoices', compact('sections', 'invoices'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoices = invoices::findOrFail($request->invoice_id);
        $invoices->update([
            $old_invoice_number =
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        $details = invoices_details::where('id_Invoice', $request->invoice_id)->first();
        $details->update([
            'invoice_number' => $request->invoice_number,
            'Section' => $request->Section,
            'product' => $request->product,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => Auth::user()->name,
        ]);
        $attachments = invoices_attachments::where('invoice_id', $request->invoice_id)->first();
        if ($attachments) {
            $attachments->update([
                'invoice_number' => $request->invoice_number,
            ]);
            File::move(public_path('Attachments/' . $request->old_invoice_number), (public_path('Attachments/' . $request->invoice_number)));
        }
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $attachments = invoices_attachments::where('invoice_id', $id)->first();

        $id_page = $request->id_page;
        if (!$id_page == 2) {
            if (!empty($attachments->invoice_number)) {
                Storage::disk('files')->deleteDirectory($attachments->invoice_number);
            }
            $invoices->forceDelete();
            session()->flash('delete', 'تم حذف الفاتورة بنجاح');
        } else {
            $invoices->delete();
            session()->flash('transfer', 'تم أرشفة الفاتورة بنجاح');
        }
        return back();

    }
    public function getProducts(int $id)
    {
        $products = DB::table('products')->where("section_id", $id)->pluck("product_name", "id");
        return json_encode($products);
    }

    public function payment_status_show($id)
    {
        $sections = sections::all();
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.payment_status_show', compact('invoices', 'sections'));
    }
    public function payment_status_update($id, Request $request)
    {
        $invoices = invoices::findOrFail($id);

        if ($request->Status == 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('status_update', 'تم تحديث حالة دفع الفاتورة بنجاح');
        return redirect('/invoices');

    }
    public function invoice_paid()
    {
        $invoices = invoices::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }
    public function invoice_unpaid()
    {
        $invoices = invoices::where('Value_Status', 2)->get();

        return view('invoices.invoices_unpaid', compact('invoices'));

    }
    public function invoice_partial()
    {
        $invoices = invoices::where('Value_Status', 3)->get();

        return view('invoices.invoices_partial', compact('invoices'));

    }
    public function print_invoice($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.print_invoice', compact('invoices'));
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

    public function MarkAsRead_all()
    {

        $userUnreadNotification = auth()->user()->unreadNotifications;

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }

    }
}