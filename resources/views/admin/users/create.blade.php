<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('Tambah Pengelola Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-64px)]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition group">
                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.05)] sm:rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 sm:p-12">
                    <div class="mb-10 text-center sm:text-left">
                        <h3 class="text-xl font-extrabold text-slate-800">Akses Karyawan</h3>
                        <p class="text-sm text-slate-500 mt-1">Buat akun untuk admin, operator, atau pimpinan untuk mengakses sistem.</p>
                    </div>

                    <form action="{{ route('users.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <x-input-label for="name" value="Nama Lengkap" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus placeholder="John Doe" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email Address -->
                            <div>
                                <x-input-label for="email" value="Email Address" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required placeholder="email@company.com" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Level -->
                            <div>
                                <x-input-label for="id_level" value="Hak Akses / Level" />
                                <select name="id_level" id="id_level" class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm transition duration-150 py-3 px-4 font-medium text-slate-700" required>
                                    <option value="">-- Pilih Level Akses --</option>
                                    @foreach($levels as $lvl)
                                        <option value="{{ $lvl->id }}" {{ old('id_level') == $lvl->id ? 'selected' : '' }}>{{ $lvl->level_name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_level')" class="mt-2" />
                            </div>

                            <div class="py-4">
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-slate-100"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-4 bg-white text-slate-400 font-bold uppercase tracking-widest text-[10px]">Autentikasi Keamanan</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password -->
                                <div>
                                    <x-input-label for="password" value="Password" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required placeholder="••••••••" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required placeholder="••••••••" />
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50 flex items-center justify-end gap-4">
                            <a href="{{ route('users.index') }}" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Buat Akun Sekarang') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
