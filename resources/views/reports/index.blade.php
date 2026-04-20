<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penjualan Laundry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
                        <!-- Dashboard Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <!-- Total Pendapatan Card -->
                <div class="bg-white rounded-2xl shadow-md p-6 
                            border border-gray-100
                            hover:shadow-xl hover:-translate-y-1 
                            transition duration-300">

                    <!-- Title -->
                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wide mb-2">
                        Total Pendapatan (Selesai)
                    </h3>

                    <!-- Value -->
                    <p class="text-3xl font-bold text-gray-800">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </p>

                    <!-- Accent line -->
                    <div class="mt-4 h-1 w-16 bg-indigo-500 rounded-full"></div>
                </div>

            </div>

            <!-- Filter Section -->
           <div class="bg-white shadow-md rounded-2xl mb-6 border border-gray-100">

    <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

        <!-- Title -->
        <h3 class="font-semibold text-gray-700 text-lg">
            Filter Tanggal
        </h3>

        <!-- Form -->
        <form action="{{ route('reports.index') }}" method="GET"
              class="flex flex-col md:flex-row gap-4 md:items-end w-full md:w-auto">

            <!-- Dari -->
            <div class="flex flex-col">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                    Dari Tanggal
                </label>
                <input type="date" name="start_date"
                    value="{{ request('start_date') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                           outline-none transition w-full">
            </div>

            <!-- Sampai -->
            <div class="flex flex-col">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                    Sampai Tanggal
                </label>
                <input type="date" name="end_date"
                    value="{{ request('end_date') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                           outline-none transition w-full">
            </div>

            <!-- Button -->
            <div class="flex items-center gap-3 mt-2 md:mt-0">

                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 
                           text-white font-medium text-sm
                           px-5 py-2 rounded-lg
                           shadow-sm hover:shadow-md
                           transition duration-200">
                    Filter
                </button>

                @if(request('start_date') || request('end_date'))
                    <a href="{{ route('reports.index') }}"
                       class="text-sm font-medium text-gray-500 
                              hover:text-gray-700 transition">
                        Reset
                    </a>
                @endif

            </div>

        </form>
    </div>
</div>

            <!-- Table Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-t-4 border-indigo-500">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gradient-to-r from-indigo-100 to-indigo-200 text-gray-700 text-sm">
                                    <th class="py-3 px-4 border-b">ID Order</th>
                                    <th class="py-3 px-4 border-b">Tgl Order</th>
                                    <th class="py-3 px-4 border-b">Status</th>
                                    <th class="py-3 px-4 border-b">Nama Pelanggan</th>
                                    <th class="py-3 px-4 border-b">Total Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $r)
                                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-indigo-50 transition text-sm">
                                        <td class="py-3 px-4 border-b font-mono font-bold text-indigo-600">
                                            <a href="{{ route('transactions.show', $r->id) }}" class="hover:underline">
                                                {{ $r->order_code }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4 border-b">{{ $r->order_date }}</td>
                                        <td class="py-3 px-4 border-b">
                                            @if($r->guest_name)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                    Non-Member (Guest)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                    Member
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b font-medium">
                                            {{ $r->guest_name ?: ($r->customer ? $r->customer->customer_name : 'No Name') }}
                                        </td>
                                        <td class="py-3 px-4 border-b font-bold text-emerald-600">
                                            Rp {{ number_format($r->grand_total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                                            Berdasarkan filter, tidak ada data laporan transaksi selesai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
