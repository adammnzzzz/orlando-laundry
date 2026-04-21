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
            'id_customer' => 'nullable|exists:customers,id',
            'is_guest' => 'nullable',
            'is_new_customer' => 'nullable',
            'customer_name' => 'required_without:id_customer|nullable|string|max:255',
            'phone' => 'required_without:id_customer|nullable|numeric',
            'address' => 'nullable|string',
            'order_pay' => 'required|numeric|min:0',
            'services' => 'required|array',
            'services.*.id' => 'required|exists:type_of_service,id',
            'services.*.qty' => 'required|numeric|min:0.1',
            'voucher_code' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // 1. Manajemen Pelanggan
            $customerId = $request->id_customer;
            $isMemberBaru = false;
            $isGuest = $request->boolean('is_guest');

            if ($isGuest) {
                // Gunakan atau buat pelanggan "Guest"
                $guestCustomer = Customer::firstOrCreate(
                    ['phone' => '0000000000'],
                    [
                        'customer_name' => 'Guest/Non-Member',
                        'address' => 'N/A'
                    ]
                );
                $customerId = $guestCustomer->id;
                $isMemberBaru = false; // Guest bukan member baru
            } elseif (!$customerId) {
                // Cek apakah nomor telepon sudah terdaftar
                $existing = Customer::where('phone', $request->phone)->first();
                if ($existing) {
                    $customerId = $existing->id;
                } else {
                    $newCustomer = Customer::create([
                        'customer_name' => $request->customer_name,
                        'phone' => $request->phone,
                        'address' => $request->address,
                    ]);
                    $customerId = $newCustomer->id;
                    $isMemberBaru = true;
                }
            }

            // Jika bukan pendaftaran baru dan bukan guest, cek apakah ini transaksi pertamanya
            if (!$isMemberBaru && !$isGuest) {
                $isMemberBaru = TransOrder::where('id_customer', $customerId)->count() === 0;
            }

            // 2. Cek Batasan Diskon (Daily Limit)
            // Guest tidak kena daily limit karena biasanya walk-in acak, 
            // tapi secara teknis ID-nya sama. Untuk akurasi aturan, kita tetap cek.
            $alreadyGotDiscountToday = TransOrder::where('id_customer', $customerId)
                ->where('order_date', now()->toDateString())
                ->where('discount', '>', 0)
                ->exists();

            // 3. Alur Perhitungan Harga (Sequential)
            
            // Step 3a: Subtotal
            $subtotal = 0;
            $details = [];
            foreach ($request->services as $srv) {
                $service = TypeOfService::findOrFail($srv['id']);
                $itemTotal = $service->price * $srv['qty'];
                $subtotal += $itemTotal;
                
                $details[] = [
                    'id_service' => $service->id,
                    'qty' => $srv['qty'],
                    'subtotal' => $itemTotal,
                ];
            }

            // Step 3b: PPN (11%)
            $taxRate = 0.11;
            $taxAmount = $subtotal * $taxRate;
            $totalAfterPPN = $subtotal + $taxAmount;

            // Step 3c: Diskon (Hanya untuk Member, Guest tidak dapat diskon)
            $discountAmount = 0;
            $discountReason = '';

            if (!$isGuest && !$alreadyGotDiscountToday) {
                $hasVoucher = !empty($request->voucher_code);
                
                if ($isMemberBaru && $hasVoucher) {
                    // Member + Kode Voucher: 15%
                    $discountAmount = $totalAfterPPN * 0.15;
                    $discountReason = 'Member Baru + Voucher (15%)';
                } elseif ($hasVoucher) {
                    // Input Kode Voucher: 10%
                    $discountAmount = $totalAfterPPN * 0.10;
                    $discountReason = 'Voucher (10%)';
                } elseif ($isMemberBaru) {
                    // Member Baru (Tanpa Voucher): 5%
                    $discountAmount = $totalAfterPPN * 0.05;
                    $discountReason = 'Member Baru (5%)';
                }
            }

            // Step 3d: Grand Total
            $grandTotal = $totalAfterPPN - $discountAmount;

            if ($request->order_pay < $grandTotal) {
                return back()->with('error', 'Pembayaran kurang! Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'))->withInput();
            }

            // 4. Simpan Header Transaksi
            $order = TransOrder::create([
                'id_customer' => $customerId,
                'guest_name' => $isGuest ? $request->customer_name : null,
                'guest_phone' => $isGuest ? $request->phone : null,
                'order_code' => 'TEMP',
                'order_date' => now()->toDateString(),
                'order_status' => 0,
                'total' => $subtotal,        
                'discount' => $discountAmount,
                'tax' => $taxAmount,           
                'grand_total' => $grandTotal,  
                'order_pay' => $request->order_pay,
                'order_change' => $request->order_pay - $grandTotal,
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

            return redirect()->route('transactions.show', $order->id)->with('success', 'Transaksi berhasil disimpan. ' . $discountReason);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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
