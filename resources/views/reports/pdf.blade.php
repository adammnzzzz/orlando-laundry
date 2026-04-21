<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Laundry</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, p { text-align: center; margin: 5px 0; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #e6e6e6; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Laundry</h2>
    @if($request->filled('start_date') && $request->filled('end_date'))
        <p>Periode: {{ $request->start_date }} s/d {{ $request->end_date }}</p>
    @else
        <p>Semua Periode</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID Order</th>
                <th>Tgl Order</th>
                <th>Status Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $r)
                <tr>
                    <td>{{ $r->order_code }}</td>
                    <td>{{ $r->order_date }}</td>
                    <td>{{ $r->guest_name ? 'Non-Member' : 'Member' }}</td>
                    <td>{{ $r->guest_name ?: ($r->customer ? $r->customer->customer_name : 'No Name') }}</td>
                    <td class="text-right">Rp {{ number_format($r->grand_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">Total Pendapatan:</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
