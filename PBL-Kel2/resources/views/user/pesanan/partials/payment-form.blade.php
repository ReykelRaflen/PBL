<form action="{{ route('user.pesanan.uploadPayment', $pesanan) }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Metode Pembayaran (Hidden - hanya transfer bank) -->
    <input type="hidden" name="metode_pembayaran" value="Transfer Bank">
    
    <!-- Bank Pengirim -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Pengirim</label>
        <select name="bank_pengirim" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <option value="">Pilih Bank Pengirim</option>
            <option value="BCA" {{ old('bank_pengirim') == 'BCA' ? 'selected' : '' }}>BCA</option>
            <option value="Mandiri" {{ old('bank_pengirim') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
            <option value="BRI" {{ old('bank_pengirim') == 'BRI' ? 'selected' : '' }}>BRI</option>
            <option value="BNI" {{ old('bank_pengirim') == 'BNI' ? 'selected' : '' }}>BNI</option>
            <option value="CIMB Niaga" {{ old('bank_pengirim') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
            <option value="Danamon" {{ old('bank_pengirim') == 'Danamon' ? 'selected' : '' }}>Danamon</option>
            <option value="Permata" {{ old('bank_pengirim') == 'Permata' ? 'selected' : '' }}>Permata</option>
            <option value="BTN" {{ old('bank_pengirim') == 'BTN' ? 'selected' : '' }}>BTN</option>
            <option value="Lainnya" {{ old('bank_pengirim') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>
        @error('bank_pengirim')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Nama Pengirim -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim</label>
        <input type="text" name="nama_pengirim" value="{{ old('nama_pengirim', Auth::user()->name) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nama sesuai rekening pengirim" required>
        @error('nama_pengirim')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Nomor Rekening Pengirim -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening Pengirim (Opsional)</label>
        <input type="text" name="nomor_rekening_pengirim" value="{{ old('nomor_rekening_pengirim') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nomor rekening pengirim">
        @error('nomor_rekening_pengirim')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Upload Bukti Pembayaran -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</label>
        <input type="file" name="bukti_pembayaran" accept="image/*" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
        @error('bukti_pembayaran')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Keterangan -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
        <textarea name="keterangan" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
        @error('keterangan')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

      <!-- Submit Button -->
    <button type="submit" style="width: 100%; background-color: #16a34a; color: white; padding: 12px 16px; border-radius: 6px; font-weight: 500; border: none; cursor: pointer;" 
            onmouseover="this.style.backgroundColor='#15803d'" 
            onmouseout="this.style.backgroundColor='#16a34a'">
        Upload Bukti Pembayaran
    </button>



</form>