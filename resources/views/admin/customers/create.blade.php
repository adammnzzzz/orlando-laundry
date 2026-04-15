<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('Tambah Pelanggan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-64px)]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('customers.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition group">
                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.05)] sm:rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 sm:p-12">
                    <div class="mb-10 text-center sm:text-left">
                        <h3 class="text-xl font-extrabold text-slate-800">Profil Pelanggan</h3>
                        <p class="text-sm text-slate-500 mt-1">Lengkapi data diri pelanggan untuk mempermudah manajemen transaksi laundry.</p>
                    </div>

                    <form action="{{ route('customers.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-8">
                            <!-- Nama Customer -->
                            <div>
                                <x-input-label for="customer_name" value="Nama Lengkap" />
                                <x-text-input id="customer_name" name="customer_name" type="text" class="mt-1 block w-full" :value="old('customer_name')" required autofocus placeholder="Contoh: Budi Santoso" />
                                <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" value="Nomor WhatsApp / Telepon" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full pl-12" :value="old('phone')" required placeholder="08xxxxxxxxxx" />
                                </div>
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Alamat -->
                            <div>
                                <x-input-label for="address" value="Alamat Lengkap" />
                                <textarea name="address" id="address" rows="3" class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm transition duration-150 placeholder:text-slate-300" required placeholder="Masukkan alamat pengantaran/penjemputan...">{{ old('address') }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50 flex items-center justify-end gap-4">
                            <a href="{{ route('customers.index') }}" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Daftarkan Pelanggan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
