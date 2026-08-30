<form action="{{ route('role.apply') }}" method="POST" class="space-y-6">
    @csrf

    <div class="border-t border-gray-200 my-4"></div>

    <div class="space-y-2">
        <label for="ukm_id" class="text-xs font-bold uppercase tracking-wider text-gray-500 block">Asal Organisasi / UKM</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <select name="ukm_id" id="ukm_id" required class="w-full bg-white border @error('ukm_id') border-red-500 @else border-gray-200 @enderror rounded-xl pl-10 pr-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-blue-500 appearance-none cursor-pointer">
                <option value="" disabled selected hidden>Pilih UKM Politeknik Negeri Batam</option>
                @foreach($ukms as $ukm)
                    <option value="{{ $ukm->id }}" {{ old('ukm_id') == $ukm->id ? 'selected' : '' }}>{{ $ukm->nama_ukm }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
        @error('ukm_id')
            <p class="text-red-600 text-xs mt-1 font-medium flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <label for="nomor_rekening" class="text-xs font-bold uppercase tracking-wider text-gray-500 block">Nomor Rekening</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
            </div>
            <input type="text" inputmode="numeric" pattern="[0-9]*" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening') }}" placeholder="Masukkan nomor rekening aktif" required class="w-full bg-white border @error('nomor_rekening') border-red-500 @else border-gray-200 @enderror rounded-xl pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 transition">
        </div>
        <p class="text-[10px] text-gray-500 font-medium">Digunakan oleh pihak kampus untuk mencairkan hasil penjualan tiket event.</p>
        @error('nomor_rekening')
            <p class="text-red-600 text-xs mt-1 font-medium flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
        @enderror
    </div>

    <div class="border-t border-gray-200 my-4"></div>

    <div class="flex items-start">
        <input type="checkbox" id="agreeCheck" required class="mt-1 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
        <label for="agreeCheck" class="ml-3 text-sm text-gray-600 cursor-pointer select-none leading-relaxed">
            Saya menyetujui seluruh aturan dan siap bertanggung jawab sebagai Organiser di Ticketify.
        </label>
    </div>

    <div class="flex space-x-3 pt-2">
        <a href="{{ route('pembeli.event') }}" class="w-1/2 text-center border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2.5 rounded-xl transition text-sm flex items-center justify-center">
            Batal
        </a>

        <button type="submit" id="submitBtn" class="w-1/2 bg-blue-600 text-white font-medium px-4 py-2.5 rounded-xl transition text-sm opacity-50 cursor-not-allowed">
            Kirim Pengajuan
        </button>
    </div>
</form>