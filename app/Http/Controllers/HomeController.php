<?php

namespace App\Http\Controllers;

use App\Models\invoices;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        //الصفحة الرئيسية - لوحة التحكم عرض charts
        // نسبة أعداد الفواتير
        $count_all = invoices::count();
        $count_invoices1 = invoices::where('Value_Status', 1)->count();
        $count_invoices2 = invoices::where('Value_Status', 2)->count();
        $count_invoices3 = invoices::where('Value_Status', 3)->count();

        if ($count_invoices1 == 0) {
            $nspainvoices1 = 0;
        } else {
            $nspainvoices1 = $count_invoices1 / $count_all * 100;
            $nspainvoices1 = round($nspainvoices1, 1, PHP_ROUND_HALF_UP);

        }

        if ($count_invoices2 == 0) {
            $nspainvoices2 = 0;
        } else {
            $nspainvoices2 = $count_invoices2 / $count_all * 100;
            $nspainvoices2 = round($nspainvoices2, 1, PHP_ROUND_HALF_UP);

        }

        if ($count_invoices3 == 0) {
            $nspainvoices3 = 0;
        } else {
            $nspainvoices3 = $count_invoices3 / $count_all * 100;
            $nspainvoices3 = round($nspainvoices3, 1, PHP_ROUND_HALF_UP);

        }

        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 250, 'height' => 200])
            ->labels(['الفواتير المدفوعة جزئيا', 'الفواتير الغير مدفوعة', 'الفواتير المدفوعة'])
            ->datasets([
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#ff9642'],
                    'data' => [$nspainvoices3],
                ],

                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => ['#ec5858'],
                    'data' => [$nspainvoices2],
                ],

                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#81b214'],
                    'data' => [$nspainvoices1],
                ],
            ])
            ->options([]);

        /////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////

        //النسبة المئوية لقيمة الفواتير
        $total_all_invoices = invoices::sum('Total');
        $paid_invoices = invoices::where('Value_Status', 1)->sum('Total');
        $unpaid_invoices = invoices::where('Value_Status', 2)->sum('Total');
        $part_paid_invoices = invoices::where('Value_Status', 3)->sum('Total');

        if ($paid_invoices == 0) {
            $paid_invoices = 0;
        } else {
            $paid_invoices = $paid_invoices / $total_all_invoices * 100;
            $paid_invoices = round($paid_invoices, 1, PHP_ROUND_HALF_UP);
        }

        if ($unpaid_invoices == 0) {
            $unpaid_invoices = 0;
        } else {
            $unpaid_invoices = $unpaid_invoices / $total_all_invoices * 100;
            $unpaid_invoices = round($unpaid_invoices, 1, PHP_ROUND_HALF_UP);

        }

        if ($part_paid_invoices == 0) {
            $part_paid_invoices = 0;
        } else {
            $part_paid_invoices = $part_paid_invoices / $total_all_invoices * 100;
            $part_paid_invoices = round($part_paid_invoices, 1, PHP_ROUND_HALF_UP);

        }

        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 250, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة ', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214', '#ff9642'],
                    'data' => [$unpaid_invoices, $paid_invoices, $part_paid_invoices],
                ],
            ])
            ->options([]);

        /////////////////////////
        if (!'home') {

            return view('404');
        } else {
            return view('home', compact('chartjs', 'chartjs_2'));

        }
    }

}
