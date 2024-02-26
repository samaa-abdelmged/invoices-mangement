<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;

class ArchiveInvoicesController extends Controller
{
    public function show()
    {
        $invoices = invoices::onlyTrashed()->get();
        return view('invoices.archive_invoices', compact('invoices'));
    }

    public function restore(Request $request)
    {
        $id = $request->invoice_id;
        Invoices::withTrashed()->where('id', $id)->restore();
        session()->flash('restore', 'تم الغاء أرشفة الفاتورة بنجاح');
        return redirect('/invoices');
    }

    public function destroy(Request $request)
    {
        $invoices = invoices::withTrashed()->where('id', $request->invoice_id)->first();
        $invoices->forceDelete();
        session()->flash('delete', 'تم حذف الفاتورة بنجاح');
        return redirect('/archive_show');

    }
}