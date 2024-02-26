<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices_details $invoices_details)
    {
        //
    }

    public function GetInvoicesDetails(int $id)
    {
        $invoices = invoices::where('id', $id)->first();
        $invoices_details = invoices_details::where('id_Invoice', $id)->get();
        $invoices_attachments = invoices_attachments::where('invoice_id', $id)->get();

        return view('invoices.show_invoices_details', compact('invoices', 'invoices_details', 'invoices_attachments'));
    }

    public function ViewFile($invoice_number, $file_name)
    {
        $dir = "Attachments";
        $file = public_path($dir . '/' . $invoice_number . '/' . $file_name);
        return response()->file($file);
    }
    public function DownloadFile($invoice_number, $file_name)
    {
        $dir = "Attachments";
        $file = public_path($dir . '/' . $invoice_number . '/' . $file_name);
        return response()->download($file);
    }

    public function DeleteFile(request $request)
    {
        $invoices = invoices_attachments::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('files')->delete($request->invoice_number . '/' . $request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

}