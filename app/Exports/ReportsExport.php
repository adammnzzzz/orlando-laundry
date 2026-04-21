<?php

namespace App\Exports;

use App\Models\TransOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $search;

    public function __construct($startDate, $endDate, $search = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->search = $search;
    }

    public function collection()
    {
        $query = TransOrder::where('order_status', 1)->with('customer');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('order_date', [$this->startDate, $this->endDate]);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID Order',
            'Tgl Order',
            'Status Pelanggan',
            'Nama Pelanggan',
            'Total Tagihan',
        ];
    }

    public function map($order): array
    {
        $statusPelanggan = $order->guest_name ? 'Non-Member (Guest)' : 'Member';
        $namaPelanggan = $order->guest_name ?: ($order->customer ? $order->customer->customer_name : 'No Name');

        return [
            $order->order_code,
            $order->order_date,
            $statusPelanggan,
            $namaPelanggan,
            $order->grand_total,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
