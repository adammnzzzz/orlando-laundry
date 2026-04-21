<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Customers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Customer</h3>
                        <a href="{{ route('customers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                            + Tambah Customer
                        </a>
                    </div>

                    <form method="GET" action="{{ route('customers.index') }}" class="mb-4 flex flex-col md:flex-row gap-4 items-center">
                        <div>
                            <select name="per_page" onchange="this.form.submit()" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per halaman</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
                                <option value="75" {{ request('per_page') == 75 ? 'selected' : '' }}>75 per halaman</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per halaman</option>
                            </select>
                        </div>
                        <div class="flex-grow w-full md:w-auto">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama customer..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm flex-grow">
                        </div>
                        <div class="flex space-x-2 w-full md:w-auto justify-end">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">Cari</button>
                            @if(request('search') || request('per_page'))
                                <a href="{{ route('customers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition flex items-center justify-center">Reset</a>
                            @endif
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-3 px-4 border-b text-center w-16">No.</th>
                                    <th class="py-3 px-4 border-b">Nama Customer</th>
                                    <th class="py-3 px-4 border-b">Phone</th>
                                    <th class="py-3 px-4 border-b">Alamat</th>
                                    <th class="py-3 px-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $c)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 border-b text-center">{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                                        <td class="py-3 px-4 border-b font-semibold">{{ $c->customer_name }}</td>
                                        <td class="py-3 px-4 border-b">{{ $c->phone }}</td>
                                        <td class="py-3 px-4 border-b">{{ Str::limit($c->address, 30) }}</td>
                                        <td class="py-3 px-4 border-b text-center">
                                            <a href="{{ route('customers.edit', $c->id) }}" class="text-blue-500 hover:text-blue-700 mx-2">Edit</a>
                                            <form action="{{ route('customers.destroy', $c->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 mx-2">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 px-4 text-center text-gray-500">Belum ada data customer.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
