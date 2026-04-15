<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kasir Laundry (Transaksi Baru)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 border-t-4 border-indigo-500">
                    
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('transactions.store') }}" method="POST" id="form-transaction">
                        @csrf
                        
                        <!-- Customer Selection -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Customer</label>
                            <select name="id_customer" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('id_customer') == $c->id ? 'selected' : '' }}>
                                        {{ $c->customer_name }} ({{ $c->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_customer') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Services List -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-700">Daftar Layanan</h3>
                                <button type="button" id="btn-add-service" class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold py-1 px-3 rounded text-sm transition">
                                    + Tambah Layanan
                                </button>
                            </div>

                            <div id="services-container" class="space-y-4">
                                <!-- First Row -->
                                <div class="service-row flex gap-4 items-end bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Layanan</label>
                                        <select name="services[0][id]" class="service-select w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                            <option value="" data-price="0">-- Pilih --</option>
                                            @foreach($services as $s)
                                                <option value="{{ $s->id }}" data-price="{{ $s->price }}">{{ $s->service_name }} (Rp {{ number_format($s->price, 0, ',', '.') }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-24">
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Qty (Kg/Pc)</label>
                                        <input type="number" name="services[0][qty]" class="service-qty w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" value="1" required min="1">
                                    </div>
                                    <div class="w-32">
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Subtotal</label>
                                        <input type="text" class="service-subtotal w-full border-gray-200 bg-gray-100 rounded-md shadow-sm text-sm" readonly value="0">
                                    </div>
                                    <div class="w-auto">
                                        <button type="button" class="btn-remove-service text-red-500 hover:text-red-700 p-2 opacity-50 cursor-not-allowed" disabled>Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Total -->
                        <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 flex flex-col md:flex-row gap-6 justify-between items-center">
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-800 mb-2">Uang Pembayaran (Rp)</label>
                                <input type="number" name="order_pay" id="order_pay" class="w-full text-lg font-bold border-indigo-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0" required min="0" value="{{ old('order_pay') }}">
                                @error('order_pay') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="text-right">
                                <div class="text-gray-500 text-sm font-semibold">Total Tagihan</div>
                                <div class="text-4xl font-extrabold text-indigo-700">Rp <span id="grand-total-text">0</span></div>
                                <input type="hidden" id="grand_total_hidden" value="0">
                                
                                <div class="mt-2 text-sm">
                                    Kembalian: <span id="change-text" class="font-bold text-gray-800">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:-translate-y-1">
                                Proses Check-Out & Cetak Invoice
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Template for new row -->
    <template id="service-row-template">
        <div class="service-row flex gap-4 items-end bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-700 mb-1">Layanan</label>
                <select name="services[INDEX][id]" class="service-select w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    <option value="" data-price="0">-- Pilih --</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}" data-price="{{ $s->price }}">{{ $s->service_name }} (Rp {{ number_format($s->price, 0, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-24">
                <label class="block text-xs font-bold text-gray-700 mb-1">Qty (Kg/Pc)</label>
                <input type="number" name="services[INDEX][qty]" class="service-qty w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" value="1" required min="1">
            </div>
            <div class="w-32">
                <label class="block text-xs font-bold text-gray-700 mb-1">Subtotal</label>
                <input type="text" class="service-subtotal w-full border-gray-200 bg-gray-100 rounded-md shadow-sm text-sm" readonly value="0">
            </div>
            <div class="w-auto">
                <button type="button" class="btn-remove-service text-red-500 hover:text-red-700 p-2 font-semibold">Hapus</button>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let serviceIndex = 1;
            const container = document.getElementById("services-container");
            const btnAdd = document.getElementById("btn-add-service");
            const template = document.getElementById("service-row-template").innerHTML;

            const updateTotals = () => {
                let grandTotal = 0;
                document.querySelectorAll(".service-row").forEach(row => {
                    const select = row.querySelector(".service-select");
                    const qty = row.querySelector(".service-qty").value;
                    const price = select.options[select.selectedIndex]?.getAttribute("data-price") || 0;
                    const subtotal = parseInt(price) * parseInt(qty || 0);
                    
                    row.querySelector(".service-subtotal").value = new Intl.NumberFormat('id-ID').format(subtotal);
                    grandTotal += subtotal;
                });

                document.getElementById("grand-total-text").innerText = new Intl.NumberFormat('id-ID').format(grandTotal);
                document.getElementById("grand_total_hidden").value = grandTotal;
                
                calculateChange();
            };

            const calculateChange = () => {
                const grandTotal = parseInt(document.getElementById("grand_total_hidden").value || 0);
                const pay = parseInt(document.getElementById("order_pay").value || 0);
                const change = pay - grandTotal;
                
                const changeText = document.getElementById("change-text");
                if (change < 0) {
                    changeText.innerText = "Kurang Rp " + new Intl.NumberFormat('id-ID').format(Math.abs(change));
                    changeText.className = "font-bold text-red-500";
                } else {
                    changeText.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(change);
                    changeText.className = "font-bold text-green-600";
                }
            };

            btnAdd.addEventListener("click", () => {
                const newRowHtml = template.replace(/INDEX/g, serviceIndex);
                container.insertAdjacentHTML('beforeend', newRowHtml);
                serviceIndex++;
                updateTotals();
            });

            container.addEventListener("input", (e) => {
                if (e.target.classList.contains('service-select') || e.target.classList.contains('service-qty')) {
                    updateTotals();
                }
            });

            container.addEventListener("click", (e) => {
                if (e.target.classList.contains('btn-remove-service')) {
                    e.target.closest('.service-row').remove();
                    updateTotals();
                }
            });

            document.getElementById("order_pay").addEventListener("input", calculateChange);
            
            // Initial calculation
            updateTotals();
        });
    </script>
</x-app-layout>
