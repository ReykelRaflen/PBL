@extends('admin.layouts.app')

@section('title', 'Detail Desain')

@section('main')
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.designs.index') }}"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 mr-4">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Desain</h1>
            </div>

            <div class="flex items-center gap-3">
                @if($design->status === 'review')
                    <button onclick="approveDesign({{ $design->id }})"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        Setujui
                    </button>
                    <button onclick="rejectDesign({{ $design->id }})"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Tolak
                    </button>
                @endif

                <a href="{{ route('admin.designs.edit', $design) }}"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Design Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $design->judul }}
                                </h2>
                                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    <span>Dibuat: {{ $design->created_at->format('d M Y, H:i') }}</span>
                                    @if($design->direview_pada)
                                        <span>Direview: {{ $design->direview_pada->format('d M Y, H:i') }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $design->status_badge }}">
                                {{ $design->status_label }}
                            </span>
                        </div>

                        @if($design->deskripsi)
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</h3>
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $design->deskripsi }}</p>
                            </div>
                        @endif

                        @if($design->catatan)
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</h3>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $design->catatan }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Cover Preview -->
                        @if($design->cover)
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cover Desain</h3>
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <img src="{{ $design->cover_url }}" alt="{{ $design->judul }}"
                                        class="max-w-full h-auto rounded-lg cursor-pointer"
                                        onclick="previewImage('{{ $design->cover_url }}', '{{ $design->judul }}')">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status History -->
                @if(isset($design->statusHistories) && $design->statusHistories->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Riwayat Status</h3>
                            <div class="space-y-4">
                                @foreach($design->statusHistories as $history)
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                            <i data-lucide="clock" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Status diubah dari
                                                    "{{ \App\Models\Design::getStatusOptions()[$history->status_from] ?? $history->status_from }}"
                                                    ke
                                                    "{{ \App\Models\Design::getStatusOptions()[$history->status_to] ?? $history->status_to }}"
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $history->created_at->format('d M Y, H:i') }} oleh
                                                {{ $history->user->name ?? 'System' }}
                                            </div>
                                            @if($history->catatan)
                                                <div
                                                    class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 rounded p-2">
                                                    {{ $history->catatan }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Design Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Desain</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Pembuat</label>
                                <div class="mt-1 flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                            {{ substr($design->pembuat->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $design->pembuat->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $design->pembuat->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($design->reviewer)
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Reviewer</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                                {{ substr($design->reviewer->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $design->reviewer->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $design->reviewer->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                                <div class="mt-1">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $design->status_badge }}">
                                        {{ $design->status_label }}
                                    </span>
                                    @if($design->due_date && $design->due_date->lt(now()) && in_array($design->status, ['draft', 'review']))
                                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Terlambat
                                        </span>
                                    @elseif($design->due_date && $design->due_date->lte(now()->addDays(3)) && in_array($design->status, ['draft', 'review']))
                                        <span
                                            class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Urgent
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($design->due_date)
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Deadline</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $design->due_date->format('d M Y') }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            ({{ $design->due_date->diffForHumans() }})
                                        </span>
                                    </p>
                                </div>
                            @endif

                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Dibuat</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $design->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>

                            @if($design->direview_pada)
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Review</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $design->direview_pada->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            @endif

                            @if($design->cover)
                                <div>
                                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Info Cover</label>
                                    <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <p>{{ basename($design->cover) }}</p>
                                        @if(Storage::disk('public')->exists($design->cover))
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ number_format(Storage::disk('public')->size($design->cover) / 1024, 2) }} KB
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
                        <div class="space-y-3">
                            @if($design->status === 'review')
                                <button onclick="approveDesign({{ $design->id }})"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                    Setujui Desain
                                </button>
                                <button onclick="rejectDesign({{ $design->id }})"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                    Tolak Desain
                                </button>
                            @endif

                            @if($design->status === 'approved')
                                <button onclick="markAsCompleted({{ $design->id }})"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Tandai Selesai
                                </button>
                            @endif

                            <a href="{{ route('admin.designs.edit', $design) }}"
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                                Edit Desain
                            </a>

                            <button onclick="updateStatus({{ $design->id }})"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                Update Status
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Related Designs -->
                @if(isset($relatedDesigns) && $relatedDesigns->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Desain Lainnya dari
                                {{ $design->pembuat->name }}</h3>
                            <div class="space-y-3">
                                @foreach($relatedDesigns as $related)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        @if($related->cover)
                                            <img src="{{ $related->cover_url }}" alt="{{ $related->judul }}"
                                                class="w-10 h-10 object-cover rounded">
                                        @else
                                            <div
                                                class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                                <i data-lucide="image" class="w-4 h-4 text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.designs.show', $related) }}"
                                                class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 truncate block">
                                                {{ $related->judul }}
                                            </a>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span
                                                    class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $related->status_badge }}">
                                                    {{ $related->status_label }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $related->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Preview Image -->
    <div id="imagePreviewModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl max-h-full overflow-auto">
            <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                <h3 id="previewTitle" class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
                <button onclick="closeImagePreview()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="p-4">
                <img id="previewImage" src="" alt="" class="max-w-full h-auto">
            </div>
        </div>
    </div>

    <!-- Modal Approve Design -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
            <form id="approveForm" method="POST">
                @csrf
                @method('POST')
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Setujui Desain</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Tambahkan catatan persetujuan..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeApproveModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            Setujui
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reject Design -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
            <form id="rejectForm" method="POST">
                @csrf
                @method('POST')
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tolak Desain</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="catatan" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            Tolak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Update Status -->
    <div id="updateStatusModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
            <form id="updateStatusForm" method="POST">
                @csrf
                @method('POST')
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Status</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status Baru <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach(\App\Models\Design::getStatusOptions() as $key => $label)
                                <option value="{{ $key }}" {{ $design->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Tambahkan catatan..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeUpdateStatusModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Image preview functions
    function previewImage(url, title) {
        document.getElementById('previewImage').src = url;
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('imagePreviewModal').classList.remove('hidden');
    }

    function closeImagePreview() {
        document.getElementById('imagePreviewModal').classList.add('hidden');
    }

    // Approve design functions
    function approveDesign(designId) {
        document.getElementById('approveForm').action = `/admin/dashboard/designs/${designId}/setujui`;
        document.getElementById('approveModal').classList.remove('hidden');
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
        document.getElementById('approveForm').reset();
    }

    // Reject design functions
    function rejectDesign(designId) {
        document.getElementById('rejectForm').action = `/admin/dashboard/designs/${designId}/tolak`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectForm').reset();
    }

    // Update status functions
    function updateStatus(designId) {
        document.getElementById('updateStatusForm').action = `/admin/dashboard/designs/${designId}/update-status`;
        document.getElementById('updateStatusModal').classList.remove('hidden');
    }

    function closeUpdateStatusModal() {
        document.getElementById('updateStatusModal').classList.add('hidden');
        document.getElementById('updateStatusForm').reset();
    }

    // Mark as completed function
    function markAsCompleted(designId) {
        if (confirm('Tandai desain ini sebagai selesai?')) {
            fetch(`/admin/dashboard/designs/${designId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: 'completed',
                    catatan: 'Desain telah selesai'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('error', 'Terjadi kesalahan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat memproses permintaan: ' + error.message);
            });
        }
    }

    // Close modals when clicking outside
    document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImagePreview();
        }
    });

    document.getElementById('approveModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeApproveModal();
        }
    });

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });

    document.getElementById('updateStatusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUpdateStatusModal();
        }
    });

    // Handle form submissions
    document.getElementById('approveForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = this.action;
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                closeApproveModal();
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', 'Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memproses permintaan: ' + error.message);
        });
    });

    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = this.action;
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                closeRejectModal();
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', 'Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memproses permintaan: ' + error.message);
        });
    });

    document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = this.action;
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                closeUpdateStatusModal();
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', 'Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memproses permintaan: ' + error.message);
        });
    });

    // Alert function
    function showAlert(type, message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert-notification');
        existingAlerts.forEach(alert => alert.remove());

        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
            type === 'success' 
                ? 'bg-green-100 border border-green-400 text-green-700' 
                : 'bg-red-100 border border-red-400 text-red-700'
        }`;
        
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(alertDiv);
        
        // Initialize icons for the new alert
        lucide.createIcons();

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Escape key to close modals
        if (e.key === 'Escape') {
            closeImagePreview();
            closeApproveModal();
            closeRejectModal();
            closeUpdateStatusModal();
        }
    });
</script>
@endpush
