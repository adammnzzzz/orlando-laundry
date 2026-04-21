<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Info -->
            <div class="bg-indigo-600 rounded-2xl shadow-lg p-6 md:p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-indigo-100">Ringkasan cepat bisnis laundry Anda hari ini.</p>
                </div>
                <div class="absolute right-0 top-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] w-full h-full"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Customer Stats -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-1">Total Pelanggan</p>
                            <h4 class="text-3xl font-bold text-gray-800">{{ $totalCustomers }}</h4>
                        </div>
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Active Orders -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-1">Belum Diambil</p>
                            <h4 class="text-3xl font-bold text-gray-800">{{ $activeOrders }}</h4>
                            <p class="text-xs text-orange-500 font-semibold mt-2">Cucian sedang diproses / Menunggu</p>
                        </div>
                        <div class="bg-orange-100 text-orange-600 p-3 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-emerald-500 rounded-2xl shadow-sm p-6 text-white hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wide mb-1">Total Pendapatan Bersih</p>
                            <h4 class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                            <p class="text-xs text-emerald-100 mt-2">Semua transaksi selesai</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Snippet -->
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-700">5 Transaksi Terbaru</h3>
                    <a href="{{ route('reports.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">Lihat Laporan Lengkap &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="py-3 px-6">ID Order</th>
                                <th class="py-3 px-6">Pelanggan</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6">Total Tagihan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-6 font-mono font-semibold">{{ $order->order_code }}</td>
                                <td class="py-3 px-6">{{ $order->guest_name ?: ($order->customer ? $order->customer->customer_name : 'No Name') }}</td>
                                <td class="py-3 px-6">
                                    @if($order->order_status == 1)
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs font-semibold">Selesai/Diambil</span>
                                    @else
                                        <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-md text-xs font-semibold">Belum Diambil</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 font-bold text-emerald-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">Belum ada transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
