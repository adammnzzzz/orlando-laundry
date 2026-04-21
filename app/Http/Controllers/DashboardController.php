<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TransOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $activeOrders = TransOrder::where('order_status', 0)->count();
        $totalRevenue = TransOrder::where('order_status', 1)->sum('grand_total');
        
        $recentOrders = TransOrder::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalCustomers',
            'activeOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
