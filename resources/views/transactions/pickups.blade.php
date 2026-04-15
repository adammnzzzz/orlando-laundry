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
                    <h3 class="text-lg font-bold mb-6">Daftar Cucian yang Belum Diambil</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
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
                                        <td class="py-3 px-4 border-b">{{ $t->customer->customer_name }}</td>
                                        <td class="py-3 px-4 border-b font-bold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
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
</x-app-layout>
