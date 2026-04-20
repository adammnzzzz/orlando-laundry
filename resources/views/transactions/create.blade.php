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
                        <div class="mb-6 bg-gray-50 p-6 rounded-lg border border-indigo-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-700">Data Pelanggan</h3>
                                <div class="flex gap-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="is_guest" name="is_guest" class="rounded border-gray-300 text-gray-600 shadow-sm focus:ring-gray-500 mr-2">
                                        <div class="flex flex-col">
                                            <label for="is_guest" class="text-sm font-semibold text-gray-600">Transaksi Guest (Non-Member)</label>
                                            <span class="text-[10px] text-red-500 font-bold italic leading-none">*Tanpa Diskon</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center" id="new-member-toggle">
                                        <input type="checkbox" id="is_new_customer" name="is_new_customer" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2">
                                        <label for="is_new_customer" class="text-sm font-semibold text-gray-700">Pelanggan Baru dan Ingin jadi Member</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Customer -->
                            <div id="existing-customer-section">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Customer</label>
                                <select name="id_customer" id="id_customer" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('id_customer') == $c->id ? 'selected' : '' }}>
                                            {{ $c->customer_name }} ({{ $c->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- New Customer -->
                            <div id="new-customer-section" class="hidden space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Pelanggan</label>
                                    <input type="text" name="customer_name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan nama lengkap">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Telepon</label>
                                        <input type="text" name="phone" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="08xxxx">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                                        <input type="text" name="address" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Alamat lengkap">
                                    </div>
                                </div>
                            </div>
                            @error('id_customer') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                                        <input type="number" name="services[0][qty]" class="service-qty w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" value="1" required min="0.1" step="0.1">
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

                        <!-- Voucher & Payment -->
                        <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <div class="mb-6">
                                        <h4 class="text-sm font-bold text-indigo-800 mb-3 uppercase tracking-wider">Info Diskon & Promo</h4>
                                        <div class="space-y-2">
                                            <div id="info-member-baru" class="flex items-center p-2 rounded bg-white border border-transparent transition">
                                                <div class="w-2 h-2 rounded-full bg-gray-300 mr-3"></div>
                                                <span class="text-sm text-gray-700">Member Baru (Tanpa Voucher): <span class="font-bold">5%</span></span>
                                            </div>
                                            <div id="info-voucher" class="flex items-center p-2 rounded bg-white border border-transparent transition">
                                                <div class="w-2 h-2 rounded-full bg-gray-300 mr-3"></div>
                                                <span class="text-sm text-gray-700">Gunakan Kode Voucher: <span class="font-bold">10%</span></span>
                                            </div>
                                            <div id="info-cumulative" class="flex items-center p-2 rounded bg-white border border-transparent transition">
                                                <div class="w-2 h-2 rounded-full bg-gray-300 mr-3"></div>
                                                <span class="text-sm text-gray-700">Member Baru + Voucher: <span class="font-bold text-indigo-600">15%</span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-800 mb-2">Kode Voucher (Opsional)</label>
                                        <input type="text" name="voucher_code" id="voucher_code" class="w-full border-indigo-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan kode voucher">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-800 mb-2">Uang Pembayaran (Rp)</label>
                                        <input type="number" name="order_pay" id="order_pay" class="w-full text-lg font-bold border-indigo-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0" required min="0" value="{{ old('order_pay') }}">
                                        @error('order_pay') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="text-right space-y-1 self-end">
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Subtotal:</span>
                                        <span id="subtotal-text">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-indigo-600">
                                        <span>PPN (11%):</span>
                                        <span id="tax-text">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-red-600 font-bold" id="discount-row" style="display: none;">
                                        <span>Diskon:</span>
                                        <span id="discount-text">- Rp 0</span>
                                    </div>
                                    <div class="pt-2 border-t border-indigo-200">
                                        <div class="text-gray-500 text-xs font-semibold uppercase">Total Tagihan (Grand Total)</div>
                                        <div class="text-4xl font-extrabold text-indigo-700">Rp <span id="grand-total-text">0</span></div>
                                    </div>
                                    <input type="hidden" id="grand_total_hidden" value="0">
                                    
                                    <div class="mt-2 text-sm pt-2">
                                        Kembalian: <span id="change-text" class="font-bold text-gray-800">Rp 0</span>
                                    </div>
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
                <input type="number" name="services[INDEX][qty]" class="service-qty w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" value="1" required min="0.1" step="0.1">
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
            
            // New Customer Toggle
            const isNewCheck = document.getElementById('is_new_customer');
            const isGuestCheck = document.getElementById('is_guest');
            const existingSection = document.getElementById('existing-customer-section');
            const newSection = document.getElementById('new-customer-section');
            const newMemberToggle = document.getElementById('new-member-toggle');

            const handleToggles = () => {
                if (isGuestCheck.checked) {
                    existingSection.classList.add('hidden');
                    newSection.classList.remove('hidden'); // Guest still needs to input name/phone
                    newMemberToggle.classList.add('opacity-50', 'pointer-events-none');
                    isNewCheck.checked = false;
                } else {
                    newMemberToggle.classList.remove('opacity-50', 'pointer-events-none');
                    if (isNewCheck.checked) {
                        existingSection.classList.add('hidden');
                        newSection.classList.remove('hidden');
                    } else {
                        existingSection.classList.remove('hidden');
                        newSection.classList.add('hidden');
                    }
                }
                updateTotals();
            };

            isNewCheck.addEventListener('change', handleToggles);
            isGuestCheck.addEventListener('change', handleToggles);

            const updateTotals = () => {
                let subtotal = 0;
                document.querySelectorAll(".service-row").forEach(row => {
                    const select = row.querySelector(".service-select");
                    const qty = row.querySelector(".service-qty").value;
                    const price = select.options[select.selectedIndex]?.getAttribute("data-price") || 0;
                    const itemSubtotal = parseInt(price) * parseFloat(qty || 0);
                    
                    row.querySelector(".service-subtotal").value = new Intl.NumberFormat('id-ID').format(itemSubtotal);
                    subtotal += itemSubtotal;
                });

                // Hitung PPN 11%
                const taxAmount = subtotal * 0.11;
                const totalAfterPPN = subtotal + taxAmount;

                // Hitung Diskon (Preview Logic)
                let discountRate = 0;
                const isGuest = isGuestCheck.checked;
                const isMemberBaru = isNewCheck.checked && !isGuest; 
                const hasVoucher = document.getElementById('voucher_code').value.trim() !== '';

                // Reset highlights
                ['info-member-baru', 'info-voucher', 'info-cumulative'].forEach(id => {
                    const el = document.getElementById(id);
                    el.classList.remove('border-indigo-500', 'bg-indigo-100', 'ring-2', 'ring-indigo-200');
                    el.querySelector('div').classList.replace('bg-indigo-500', 'bg-gray-300');
                });

                if (isGuest) {
                    discountRate = 0;
                    // Highlight that Guests don't get discounts
                    ['info-member-baru', 'info-voucher', 'info-cumulative'].forEach(id => {
                        const el = document.getElementById(id);
                        el.classList.add('opacity-30');
                    });
                } else {
                    // Reset opacity for members
                    ['info-member-baru', 'info-voucher', 'info-cumulative'].forEach(id => {
                        document.getElementById(id).classList.remove('opacity-30');
                    });

                    if (isMemberBaru && hasVoucher) {
                        discountRate = 0.15;
                        highlightPromo('info-cumulative');
                    } else if (hasVoucher) {
                        discountRate = 0.10;
                        highlightPromo('info-voucher');
                    } else if (isMemberBaru) {
                        discountRate = 0.05;
                        highlightPromo('info-member-baru');
                    }
                }

                function highlightPromo(id) {
                    const el = document.getElementById(id);
                    el.classList.add('border-indigo-500', 'bg-indigo-100', 'ring-2', 'ring-indigo-200');
                    el.querySelector('div').classList.replace('bg-gray-300', 'bg-indigo-500');
                }

                const discountAmount = totalAfterPPN * discountRate;
                const grandTotal = totalAfterPPN - discountAmount;

                // Tampilkan di UI
                document.getElementById("subtotal-text").innerText = "Rp " + new Intl.NumberFormat('id-ID').format(subtotal);
                document.getElementById("tax-text").innerText = "Rp " + new Intl.NumberFormat('id-ID').format(taxAmount);
                
                const discRow = document.getElementById("discount-row");
                const discText = document.getElementById("discount-text");
                if (discountAmount > 0) {
                    discRow.style.display = "flex";
                    discText.innerText = "- Rp " + new Intl.NumberFormat('id-ID').format(discountAmount);
                } else {
                    discRow.style.display = "none";
                }

                document.getElementById("grand-total-text").innerText = new Intl.NumberFormat('id-ID').format(grandTotal);
                document.getElementById("grand_total_hidden").value = grandTotal;
                
                calculateChange();
            };

            const calculateChange = () => {
                const grandTotal = parseFloat(document.getElementById("grand_total_hidden").value || 0);
                const pay = parseInt(document.getElementById("order_pay").value || 0);
                const change = pay - grandTotal;
                
                const changeText = document.getElementById("change-text");
                if (change < 0) {
                    changeText.innerText = "Kurang Rp " + new Intl.NumberFormat('id-ID').format(Math.abs(change));
                    changeText.className = "font-bold text-red-500";
                } else {
                    changeText.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(Math.floor(change));
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
            document.getElementById("voucher_code").addEventListener("input", updateTotals);
            
            // Initial calculation
            updateTotals();
        });
    </script>
</x-app-layout>
