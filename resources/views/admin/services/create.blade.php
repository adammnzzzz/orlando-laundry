<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('Tambah Layanan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-64px)]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('services.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition group">
                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.05)] sm:rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 sm:p-12">
                    <div class="mb-10 text-center sm:text-left">
                        <h3 class="text-xl font-extrabold text-slate-800">Detail Layanan</h3>
                        <p class="text-sm text-slate-500 mt-1">Masukkan informasi jasa laundry yang ingin Anda tawarkan kepada pelanggan.</p>
                    </div>

                    <form action="{{ route('services.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-8">
                            <!-- Nama Layanan -->
                            <div>
                                <x-input-label for="service_name" value="Nama Layanan" />
                                <x-text-input id="service_name" name="service_name" type="text" class="mt-1 block w-full" :value="old('service_name')" required autofocus placeholder="Contoh: Cuci Kering Setrika" />
                                <x-input-error :messages="$errors->get('service_name')" class="mt-2" />
                            </div>

                            <!-- Harga -->
                            <div>
                                <x-input-label for="price" value="Harga per Satuan (Rp)" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold text-sm">Rp</span>
                                    </div>
                                    <x-text-input id="price" name="price" type="number" class="mt-1 block w-full pl-12" :value="old('price')" required min="0" placeholder="0" />
                                </div>
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <x-input-label for="description" value="Deskripsi (Opsional)" />
                                <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm transition duration-150 placeholder:text-slate-300" placeholder="Jelaskan detail layanan ini...">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50 flex items-center justify-end gap-4">
                            <a href="{{ route('services.index') }}" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Layanan Sekarang') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
