@extends('admin.layouts.app')

@section('title', 'Manajemen Desain')

@section('main')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Desain</h1>
        <a href="{{ route('admin.designs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Desain
        </a>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i data-lucide="layout" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Desain</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Draft</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['draft'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i data-lucide="eye" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Review</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['review'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Disetujui</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari desain..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Design::getStatusOptions() as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="pembuat" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Pembuat</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('pembuat') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Filter
            </button>
            <a href="{{ route('admin.designs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                Reset
            </a>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
        <form id="bulkActionForm" method="POST" action="{{ route('admin.designs.bulk-action') }}">
            @csrf
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex items-center">
                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <label for="selectAll" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Pilih Semua</label>
                </div>
                
                <select name="action" id="bulkAction" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Aksi</option>
                    <option value="setujui">Setujui</option>
                    <option value="tolak">Tolak</option>
                    <option value="update_status">Update Status</option>
                    <option value="hapus">Hapus</option>
                </select>

                <div id="statusSelect" class="hidden">
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Status</option>
                        @foreach(\App\Models\Design::getStatusOptions() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="catatanInput" class="hidden flex-1">
                    <input type="text" name="catatan" placeholder="Catatan (opsional)" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <button type="submit" id="bulkSubmit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg disabled:opacity-50" disabled>
                    Jalankan
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Desain -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAllTable" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cover</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pembuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Buat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($designs as $design)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="design_ids[]" value="{{ $design->id }}" class="design-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($design->cover)
                                <img src="{{ $design->cover_url }}" alt="Cover" class="w-12 h-12 object-cover rounded-lg cursor-pointer" onclick="previewImage('{{ $design->cover_url }}', '{{ $design->judul }}')">
                            @else
                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                    <i data-lucide="image" class="w-6 h-6 text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $design->judul }}</div>
                            @if($design->deskripsi)
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($design->deskripsi, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $design->pembuat->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $design->status_badge }}">
                                {{ $design->status_label }}
                            </span>
                            @if($design->is_overdue)
                                <span class="ml-1 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Terlambat
                                </span>
                            @elseif($design->is_urgent)
                                <span class="ml-1 px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Urgent
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $design->due_date ? $design->due_date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $design->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.designs.show', $design) }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" 
                                   title="Lihat Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.designs.edit', $design) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                   title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                
                                @if($design->status === 'review')
                                    <button onclick="approveDesign({{ $design->id }})" 
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                            title="Setujui">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="rejectDesign({{ $design->id }})" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Tolak">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                @endif

                                <form action="{{ route('admin.designs.destroy', $design) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Hapus"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus desain ini?')">
                                                                             <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="folder-open" class="w-12 h-12 text-gray-400 mb-4"></i>
                                <p class="text-gray-500 dark:text-gray-400 text-lg mb-2">Tidak ada desain ditemukan</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm">Mulai dengan menambahkan desain baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($designs->hasPages())
    <div class="mt-6">
        {{ $designs->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Modal Preview Image -->
<div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
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
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Setujui Desain</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea name="catatan" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                              placeholder="Tambahkan catatan..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeApproveModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
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
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                        Tolak
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

    // Bulk action functionality
    document.getElementById('bulkAction').addEventListener('change', function() {
        const action = this.value;
        const statusSelect = document.getElementById('statusSelect');
        const catatanInput = document.getElementById('catatanInput');
        
        statusSelect.classList.add('hidden');
        catatanInput.classList.add('hidden');
        
        if (action === 'update_status') {
            statusSelect.classList.remove('hidden');
        }
        
        if (['setujui', 'tolak', 'update_status'].includes(action)) {
            catatanInput.classList.remove('hidden');
        }
    });

    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.design-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkSubmitButton();
    });

    document.getElementById('selectAllTable').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.design-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkSubmitButton();
    });

    // Individual checkbox change
    document.querySelectorAll('.design-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkSubmitButton);
    });

    function updateBulkSubmitButton() {
        const checkedBoxes = document.querySelectorAll('.design-checkbox:checked');
        const submitButton = document.getElementById('bulkSubmit');
        submitButton.disabled = checkedBoxes.length === 0;
    }

    // Bulk form submission
    document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
        const action = document.getElementById('bulkAction').value;
        const checkedBoxes = document.querySelectorAll('.design-checkbox:checked');
        
        if (!action) {
            e.preventDefault();
            alert('Pilih aksi yang akan dilakukan!');
            return;
        }
        
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu desain!');
            return;
        }
        
        let confirmMessage = '';
        switch (action) {
            case 'setujui':
                confirmMessage = `Setujui ${checkedBoxes.length} desain?`;
                break;
            case 'tolak':
                confirmMessage = `Tolak ${checkedBoxes.length} desain?`;
                break;
            case 'hapus':
                confirmMessage = `Hapus ${checkedBoxes.length} desain? Tindakan ini tidak dapat dibatalkan!`;
                break;
            case 'update_status':
                const status = document.querySelector('select[name="status"]').value;
                if (!status) {
                    e.preventDefault();
                    alert('Pilih status yang akan diupdate!');
                    return;
                }
                confirmMessage = `Update status ${checkedBoxes.length} desain ke ${status}?`;
                break;
        }
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });

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
        document.getElementById('approveForm').action = `/admin/designs/${designId}/setujui`;
        document.getElementById('approveModal').classList.remove('hidden');
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
        document.getElementById('approveForm').reset();
    }

    // Reject design functions
    function rejectDesign(designId) {
        document.getElementById('rejectForm').action = `/admin/designs/${designId}/tolak`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectForm').reset();
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
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
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
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    });
</script>
@endpush
