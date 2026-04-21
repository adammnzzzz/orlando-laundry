<?php

namespace App\Http\Controllers;

use App\Models\TransOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = TransOrder::where('order_status', 1);

        $pendapatanHariIni = (clone $baseQuery)->whereDate('order_date', Carbon::today())->sum('grand_total');
        $pendapatanMingguIni = (clone $baseQuery)->whereBetween('order_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('grand_total');
        $pendapatanBulanIni = (clone $baseQuery)->whereMonth('order_date', Carbon::now()->month)
                                                ->whereYear('order_date', Carbon::now()->year)
                                                ->sum('grand_total');

        $query = (clone $baseQuery)->with('customer');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('order_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        // Calculate total for all filtered data, not just the current page
        $totalPendapatanQuery = clone $query;
        $totalPendapatan = $totalPendapatanQuery->sum('grand_total');

        $perPage = $request->query('per_page', 25);
        $reports = $query->latest()->paginate($perPage)->withQueryString();

        return view('reports.index', compact(
            'reports', 
            'totalPendapatan',
            'pendapatanHariIni',
            'pendapatanMingguIni',
            'pendapatanBulanIni'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = TransOrder::where('order_status', 1)->with('customer');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('order_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->latest()->get();
        $totalPendapatan = $reports->sum('grand_total');

        $pdf = Pdf::loadView('reports.pdf', compact('reports', 'totalPendapatan', 'request'));
        return $pdf->download('laporan-penjualan.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new ReportsExport($request->start_date, $request->end_date, $request->search), 'laporan-penjualan.xlsx');
    }
}
