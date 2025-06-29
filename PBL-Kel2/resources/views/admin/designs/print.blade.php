<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Desain - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-draft { background: #6c757d; color: white; }
        .status-review { background: #ffc107; color: black; }
        .status-approved { background: #28a745; color: white; }
        .status-rejected { background: #dc3545; color: white; }
        .status-completed { background: #17a2b8; color: white; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Manajemen Desain</h1>
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
        <p>Total Data: {{ $designs->count() }} desain</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Desain</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['draft'] }}</div>
            <div class="stat-label">Draft</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['review'] }}</div>
            <div class="stat-label">Review</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['approved'] }}</div>
            <div class="stat-label">Disetujui</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Ditolak</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['completed'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Judul</th>
                <th style="width: 15%">Pembuat</th>
                <th style="width: 10%">Status</th>
                <th style="width: 12%">Due Date</th>
                <th style="width: 12%">Dibuat</th>
                <th style="width: 12%">Direview</th>
                <th style="width: 9%">Reviewer</th>
            </tr>
        </thead>
        <tbody>
            @forelse($designs as $index => $design)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $design->judul }}</strong>
                    @if($design->deskripsi)
                        <br><small style="color: #666;">{{ Str::limit($design->deskripsi, 50) }}</small>
                    @endif
                </td>
                <td>{{ $design->pembuat->name }}</td>
                <td>
                    <span class="status-badge status-{{ $design->status }}">
                        {{ ucfirst($design->status) }}
                    </span>
                </td>
                <td>{{ $design->due_date ? $design->due_date->format('d/m/Y') : '-' }}</td>
                <td>{{ $design->created_at->format('d/m/Y') }}</td>
                <td>{{ $design->direview_pada ? $design->direview_pada->format('d/m/Y') : '-' }}</td>
                <td>{{ $design->reviewer ? $design->reviewer->name : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #666;">
                    Tidak ada data desain
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($designs->count() > 0)
    <div style="margin-top: 20px;">
        <h3>Ringkasan:</h3>
        <ul style="list-style-type: disc; margin-left: 20px;">
            <li>Total desain yang tercatat: <strong>{{ $stats['total'] }}</strong></li>
            <li>Desain dalam status draft: <strong>{{ $stats['draft'] }}</strong></li>
            <li>Desain dalam review: <strong>{{ $stats['review'] }}</strong></li>
            <li>Desain yang disetujui: <strong>{{ $stats['approved'] }}</strong></li>
            <li>Desain yang ditolak: <strong>{{ $stats['rejected'] }}</strong></li>
            <li>Desain yang selesai: <strong>{{ $stats['completed'] }}</strong></li>
        </ul>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem manajemen desain</p>
        <p>© {{ date('Y') }} - Sistem Manajemen Desain</p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
