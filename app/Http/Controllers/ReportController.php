<?php

namespace App\Http\Controllers;

use App\Models\TransOrder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = TransOrder::where('order_status', 1)->with('customer');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('order_date', [$request->start_date, $request->end_date]);
        }

        $reports = $query->latest()->get();
        $totalPendapatan = $reports->sum('grand_total');

        return view('reports.index', compact('reports', 'totalPendapatan'));
    }
}
