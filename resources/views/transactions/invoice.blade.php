<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaction->order_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 p-8">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow-lg border-t-8 border-indigo-600">
        
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-black text-indigo-700 tracking-tight">LAUNDRY APP</h1>
                <p class="text-gray-500 text-sm">Sistem Manajemen Laundry Profesional</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-gray-800">INVOICE</h2>
                <p class="text-gray-600 font-mono">{{ $transaction->order_code }}</p>
            </div>
        </div>

        <div class="flex justify-between mb-8 border-b pb-8">
            <div>
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Informasi Pelanggan</h3>
                <p class="font-bold text-lg">{{ $transaction->customer->customer_name }}</p>
                <p class="text-gray-600">{{ $transaction->customer->phone }}</p>
                <p class="text-gray-600 text-sm mt-1">{{ $transaction->customer->address }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Detail Order</h3>
                <p><span class="text-gray-500">Tanggal:</span> {{ \Carbon\Carbon::parse($transaction->order_date)->format('d M Y') }}</p>
                <p><span class="text-gray-500">Status:</span> 
                    @if($transaction->order_status == 0)
                        <span class="text-orange-500 font-bold">Baru / Proses</span>
                    @else
                        <span class="text-green-500 font-bold">Selesai / Diambil</span>
                    @endif
                </p>
            </div>
        </div>

        <table class="w-full mb-8">
            <thead>
                <tr class="border-b-2 border-gray-200 text-left">
                    <th class="py-2 text-gray-600 font-bold text-sm uppercase">Layanan</th>
                    <th class="py-2 text-gray-600 font-bold text-sm uppercase text-center w-24">Harga</th>
                    <th class="py-2 text-gray-600 font-bold text-sm uppercase text-center w-20">Qty</th>
                    <th class="py-2 text-gray-600 font-bold text-sm uppercase text-right w-32">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $d)
                <tr class="border-b border-gray-100">
                    <td class="py-3">{{ $d->service->service_name }}</td>
                    <td class="py-3 text-center">Rp {{ number_format($d->service->price, 0, ',', '.') }}</td>
                    <td class="py-3 text-center">{{ $d->qty }}</td>
                    <td class="py-3 text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end mb-8">
            <div class="w-1/2">
                <div class="flex justify-between py-1">
                    <span class="text-gray-600">Total Tagihan</span>
                    <span class="font-bold text-lg">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-600">Uang Bayar</span>
                    <span>Rp {{ number_format($transaction->order_pay, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1 border-t border-gray-200 mt-1 pt-1">
                    <span class="text-gray-600 font-bold">Kembalian</span>
                    <span class="font-bold text-indigo-600">Rp {{ number_format($transaction->order_change, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="text-center text-gray-500 text-sm mt-12 border-t pt-8">
            <p>Terima kasih telah mempercayakan cucian Anda kepada kami!</p>
        </div>

    </div>

    <div class="text-center mt-8 no-print">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-lg mr-4">
            Cetak Invoice
        </button>
        <a href="{{ route('transactions.create') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded">
            Kembali
        </a>
    </div>

</body>
</html>
