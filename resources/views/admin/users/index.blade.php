<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Users') }}
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
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Sistem User</h3>
                        <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                            + Tambah User
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-3 px-4 border-b">ID</th>
                                    <th class="py-3 px-4 border-b">Nama User</th>
                                    <th class="py-3 px-4 border-b">Email</th>
                                    <th class="py-3 px-4 border-b">Hak Akses (Level)</th>
                                    <th class="py-3 px-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $u)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 border-b">{{ $u->id }}</td>
                                        <td class="py-3 px-4 border-b font-semibold">{{ $u->name }}</td>
                                        <td class="py-3 px-4 border-b">{{ $u->email }}</td>
                                        <td class="py-3 px-4 border-b">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $u->level->level_name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border-b text-center">
                                            <a href="{{ route('users.edit', $u->id) }}" class="text-blue-500 hover:text-blue-700 mx-2">Edit</a>
                                            @if($u->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 mx-2">Hapus</button>
                                            </form>
                                            <form action="{{ route('users.reset-password', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Reset password user ini menjadi: password123?');">
                                                @csrf
                                                <button type="submit" class="text-yellow-500 hover:text-yellow-700 mx-2">Reset Pass</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 px-4 text-center text-gray-500">Belum ada data user.</td>
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
