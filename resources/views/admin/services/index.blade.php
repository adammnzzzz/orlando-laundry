<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
            {{ __('Manajemen Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Alert Success -->
            @if(session('success'))
                <div class="mb-6 flex items-center p-4 text-green-800 rounded-lg bg-green-50 border border-green-200 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Card Container -->
            <div class="bg-white shadow-xl shadow-gray-200/50 sm:rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-8">

                    <!-- Header Section -->
                    <div class="flex justify-between items-center mb-8">
                        <div class="m-4">
                            <h3 class="text-xl font-bold text-gray-900">Daftar Layanan</h3>
                            <p class="text-sm text-gray-500">Kelola informasi jasa dan harga layanan Anda.</p>
                        </div>
                        <a href="{{ route('services.create') }}" 
                           class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded-lg text-sm shadow-sm transition">
                            Tambah Layanan
                        </a>
                    </div>

                    <!-- Table Section -->
                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 font-bold tracking-wider">ID</th>
                                    <th class="px-6 py-4 font-bold tracking-wider">Informasi Layanan</th>
                                    <th class="px-6 py-4 font-bold tracking-wider">Harga Satuan</th>
                                    <th class="px-6 py-4 font-bold tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($services as $s)
                                    <tr class="hover:bg-indigo-50/30 transition-colors duration-150 group">
                                        <td class="px-6 py-4 text-gray-400 font-medium">#{{ $s->id }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-0.5">
                                                <span class="text-gray-900 font-bold text-base">{{ $s->service_name }}</span>
                                                <span class="text-gray-500 text-xs italic line-clamp-1">{{ $s->description ?? 'Tidak ada deskripsi' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                                Rp {{ number_format($s->price, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center gap-3">
                                                <!-- Edit Button -->
                                                <a href="{{ route('services.edit', $s->id) }}" 
                                                   class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" 
                                                   title="Edit Data">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <!-- Delete Button -->
                                                <form action="{{ route('services.destroy', $s->id) }}" method="POST" 
                                                      class="inline-block" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" 
                                                            title="Hapus Data">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="p-3 bg-gray-100 rounded-full">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                    </svg>
                                                </div>
                                                <p class="text-gray-500 font-medium">Belum ada layanan yang tersedia.</p>
                                                <a href="{{ route('services.create') }}" class="text-indigo-600 font-bold hover:underline">
                                                    Mulai buat layanan pertama?
                                                </a>
                                            </div>
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
