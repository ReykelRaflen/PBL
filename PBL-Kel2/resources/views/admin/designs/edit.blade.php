@extends('admin.layouts.app')

@section('title', 'Edit Desain')

@section('main')
<div class="p-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.designs.show', $design) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 mr-4">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Desain: {{ $design->judul }}</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form action="{{ route('admin.designs.update', $design) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Judul Desain <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $design->judul) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('judul') border-red-500 @enderror">
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Masukkan deskripsi desain...">{{ old('deskripsi', $design->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pembuat_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pembuat <span class="text-red-500">*</span>
                        </label>
                        <select id="pembuat_id" name="pembuat_id" required
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('pembuat_id') border-red-500 @enderror">
                            <option value="">Pilih Pembuat</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('pembuat_id', $design->pembuat_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                                               @error('pembuat_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('status') border-red-500 @enderror">
                            @foreach($statusOptions as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $design->status) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Deadline
                        </label>
                        <input type="date" id="due_date" name="due_date" 
                               value="{{ old('due_date', $design->due_date ? $design->due_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('due_date') border-red-500 @enderror">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label for="cover" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cover Desain
                        </label>
                        
                        @if($design->cover)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Cover saat ini:</p>
                                <div class="relative inline-block">
                                    <img src="{{ $design->cover_url }}" alt="Current cover" class="h-32 w-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeCover()" 
                                            class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <input type="hidden" id="remove_cover" name="remove_cover" value="0">
                            </div>
                        @endif

                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 dark:border-gray-600 dark:hover:border-gray-500">
                            <div class="space-y-1 text-center">
                                <div id="preview-container" class="hidden">
                                    <img id="preview-image" src="" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg">
                                </div>
                                <div id="upload-placeholder">
                                    <i data-lucide="upload" class="mx-auto h-12 w-12 text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="cover" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>{{ $design->cover ? 'Ganti cover' : 'Upload cover' }}</span>
                                            <input id="cover" name="cover" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF hingga 2MB</p>
                                </div>
                            </div>
                        </div>
                        @error('cover')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan
                        </label>
                        <textarea id="catatan" name="catatan" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('catatan') border-red-500 @enderror"
                                  placeholder="Tambahkan catatan atau instruksi khusus...">{{ old('catatan', $design->catatan) }}</textarea>
                        @error('catatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status History -->
                    @if($design->statusHistories && $design->statusHistories->count() > 0)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Riwayat Status</h4>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @foreach($design->statusHistories->take(5) as $history)
                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                <span class="font-medium">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                - {{ \App\Models\Design::getStatusOptions()[$history->status_from] ?? $history->status_from }} 
                                → {{ \App\Models\Design::getStatusOptions()[$history->status_to] ?? $history->status_to }}
                                @if($history->user)
                                    oleh {{ $history->user->name }}
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Info Card -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i data-lucide="info" class="h-5 w-5 text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Informasi
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Desain dibuat: {{ $design->created_at->format('d M Y, H:i') }}</li>
                                        @if($design->direview_pada)
                                            <li>Terakhir direview: {{ $design->direview_pada->format('d M Y, H:i') }}</li>
                                        @endif
                                        <li>Perubahan akan tercatat dalam riwayat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.designs.show', $design) }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Image preview functionality
    document.getElementById('cover').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-container').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove current cover
    function removeCover() {
        if (confirm('Hapus cover saat ini?')) {
            document.getElementById('remove_cover').value = '1';
            // Hide current cover preview
            const currentCover = document.querySelector('img[alt="Current cover"]').parentElement;
            currentCover.style.display = 'none';
        }
    }

    // Drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');
    
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                document.getElementById('cover').files = files;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // Set minimum date to today for due_date
    document.getElementById('due_date').min = new Date().toISOString().split('T')[0];

    // Status change confirmation
    const statusSelect = document.getElementById('status');
    const originalStatus = '{{ $design->status }}';
    
    statusSelect.addEventListener('change', function() {
        const newStatus = this.value;
        const statusLabels = @json($statusOptions);
        
        if (newStatus !== originalStatus) {
            const confirmMessage = `Ubah status dari "${statusLabels[originalStatus]}" ke "${statusLabels[newStatus]}"?`;
            if (!confirm(confirmMessage)) {
                this.value = originalStatus;
            }
        }
    });

    // Form submission confirmation
    document.querySelector('form').addEventListener('submit', function(e) {
        const currentStatus = document.getElementById('status').value;
        
        if (currentStatus !== originalStatus) {
            const statusLabels = @json($statusOptions);
            const confirmMessage = `Konfirmasi perubahan status dari "${statusLabels[originalStatus]}" ke "${statusLabels[currentStatus]}"?`;
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
        }
        
        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin mr-2"></i>Menyimpan...';
        
        // Re-enable button after 5 seconds as fallback
        setTimeout(() => {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }, 5000);
    });
</script>
@endpush
