<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengambilan Laundry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-t-4 border-indigo-500">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold">Daftar Cucian yang Belum Diambil</h3>
                        <div class="relative w-full md:w-64">
                            <input type="text" id="pickupSearch" placeholder="Cari nama atau kode order..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="pickupTable">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-3 px-4 border-b">ID Order</th>
                                    <th class="py-3 px-4 border-b">Tanggal Order</th>
                                    <th class="py-3 px-4 border-b">Nama Customer</th>
                                    <th class="py-3 px-4 border-b">Total Tagihan</th>
                                    <th class="py-3 px-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $t)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 border-b font-mono font-bold text-indigo-600">
                                            <a href="{{ route('transactions.show', $t->id) }}" class="hover:underline">{{ $t->order_code }}</a>
                                        </td>
                                        <td class="py-3 px-4 border-b">{{ $t->order_date }}</td>
                                        <td class="py-3 px-4 border-b">
                                            <div class="flex flex-col">
                                                <span class="font-bold">{{ $t->guest_name ?: $t->customer->customer_name }}</span>
                                                @if($t->guest_name)
                                                    <span class="text-[10px] text-gray-500 font-semibold uppercase">Non-Member (Guest)</span>
                                                @else
                                                    <span class="text-[10px] text-indigo-500 font-semibold uppercase">Member</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 border-b font-bold">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 border-b text-center">
                                            <form action="{{ route('transactions.complete', $t->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tandai laundry ini sudah diambil oleh customer?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-4 rounded shadow">
                                                    Selesai / Ambil
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 px-4 text-center text-gray-500">Tidak ada cucian baru yang menunggu diambil. Semua sudah bersih! ✨</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('pickupSearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#pickupTable tbody tr');
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
