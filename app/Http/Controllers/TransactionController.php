<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TypeOfService;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function create()
    {
        $customers = Customer::all();
        $services = TypeOfService::all();
        return view('transactions.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'required|exists:customers,id',
            'order_pay' => 'required|integer|min:0',
            'services' => 'required|array',
            'services.*.id' => 'required|exists:type_of_service,id',
            'services.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total
            $total = 0;
            $details = [];
            foreach ($request->services as $srv) {
                $service = TypeOfService::findOrFail($srv['id']);
                $subtotal = $service->price * $srv['qty'];
                $total += $subtotal;
                $details[] = [
                    'id_service' => $service->id,
                    'qty' => $srv['qty'],
                    'subtotal' => $subtotal,
                ];
            }

            if ($request->order_pay < $total) {
                return back()->with('error', 'Pembayaran kurang dari total!')->withInput();
            }

            $order = TransOrder::create([
                'id_customer' => $request->id_customer,
                'order_code' => 'TEMP',
                'order_date' => now()->toDateString(),
                'order_status' => 0,
                'total' => $total,
                'order_pay' => $request->order_pay,
                'order_change' => $request->order_pay - $total,
            ]);

            // Update order code with ID
            $order->update([
                'order_code' => 'LDY-' . now()->format('Ymd') . '-' . $order->id,
            ]);

            foreach ($details as $detail) {
                TransOrderDetail::create([
                    'id_order' => $order->id,
                    'id_service' => $detail['id_service'],
                    'qty' => $detail['qty'],
                    'subtotal' => $detail['subtotal'],
                ]);
            }

            DB::commit();

            return redirect()->route('transactions.show', $order->id)->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    public function show(TransOrder $transaction)
    {
        $transaction->load('customer', 'details.service');
        return view('transactions.invoice', compact('transaction'));
    }

    public function pickups()
    {
        $transactions = TransOrder::with('customer')->where('order_status', 0)->latest()->get();
        return view('transactions.pickups', compact('transactions'));
    }

    public function complete(TransOrder $transaction)
    {
        $transaction->update([
            'order_status' => 1,
            'order_end_date' => now(),
        ]);
        return redirect()->route('transactions.pickups')->with('success', 'Transaksi selesai/diambil.');
    }
}
