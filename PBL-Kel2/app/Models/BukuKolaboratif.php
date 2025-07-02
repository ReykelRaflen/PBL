<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuKolaboratif extends Model
{
    use HasFactory;

    protected $table = 'buku_kolaboratif';

    protected $fillable = [
        'judul',
        'deskripsi',
        'harga_per_bab',
        'total_bab',
        'status',
        'gambar_sampul',
        'kategori_buku_id',
        'target_pembaca'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan BabBuku
    public function babBuku()
    {
        return $this->hasMany(BabBuku::class, 'buku_kolaboratif_id');
    }

    // Relationship dengan KategoriBuku
    public function kategoriBuku()
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_buku_id');
    }

    // Relationship dengan PesananKolaborasi
    public function pesananBuku()
    {
        return $this->hasMany(PesananKolaborasi::class, 'buku_kolaboratif_id');
    }

    // Accessor untuk kategori nama
    public function getKategoriAttribute()
    {
        return $this->kategoriBuku ? $this->kategoriBuku->nama : 'Umum';
    }

    // Accessor untuk bab tersedia
    public function getBabTersediaAttribute()
    {
        return $this->babBuku()->where('status', 'tersedia')->count();
    }

    // Accessor untuk harga minimum - menggunakan harga_per_bab dari buku_kolaboratif
    public function getHargaMinimumAttribute()
    {
        // Jika ada kolom harga_per_bab di tabel buku_kolaboratif
        if (isset($this->attributes['harga_per_bab'])) {
            return $this->attributes['harga_per_bab'];
        }
        
        // Atau ambil dari relasi pesanan jika ada
        return $this->pesananBuku()->min('harga') ?? 0;
    }

    // Accessor untuk harga maksimum - menggunakan harga_per_bab dari buku_kolaboratif
    public function getHargaMaksimumAttribute()
    {
        // Jika ada kolom harga_per_bab di tabel buku_kolaboratif
        if (isset($this->attributes['harga_per_bab'])) {
            return $this->attributes['harga_per_bab'];
        }
        
        // Atau ambil dari relasi pesanan jika ada
        return $this->pesananBuku()->max('harga') ?? 0;
    }

    // Accessor untuk rentang harga
    public function getRentangHargaAttribute()
    {
        $min = $this->harga_minimum;
        $max = $this->harga_maksimum;
        
        if ($min == 0 && $max == 0) {
            return 'Belum ada harga';
        }
        
        if ($min == $max) {
            return 'Rp ' . number_format($min, 0, ',', '.');
        }
        
        return 'Rp ' . number_format($min, 0, ',', '.') . ' - Rp ' . number_format($max, 0, ',', '.');
    }

    // Accessor untuk total nilai proyek - menggunakan harga_per_bab * total_bab
    public function getTotalNilaiProyekAttribute()
    {
        // Jika ada kolom harga_per_bab di tabel buku_kolaboratif
        if (isset($this->attributes['harga_per_bab']) && isset($this->attributes['total_bab'])) {
            return $this->attributes['harga_per_bab'] * $this->attributes['total_bab'];
        }
        
        // Atau hitung dari pesanan yang ada
        return $this->pesananBuku()->sum('harga') ?? 0;
    }

    // Relationship untuk bab tersedia saja
    public function babTersedia()
    {
        return $this->hasMany(BabBuku::class, 'buku_kolaboratif_id')->where('status', 'tersedia');
    }

    // Scope untuk filter kategori
    public function scopeKategori($query, $kategoriId)
    {
        return $query->where('kategori_buku_id', $kategoriId);
    }

    // Scope untuk filter status
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk search
    public function scopeSearch($query, $search)
    {
        return $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('kategoriBuku', function($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
    }

    // Scope untuk filter berdasarkan rentang harga - menggunakan harga_per_bab
    public function scopeHargaRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('harga_per_bab', '>=', $min);
        }
        if ($max !== null) {
            $query->where('harga_per_bab', '<=', $max);
        }
        return $query;
    }

    // Method untuk mendapatkan progress buku
    public function getProgressAttribute()
    {
        $totalBab = $this->total_bab;
        $babTersedia = $this->getBabTersediaAttribute();
        $babDipesan = $this->babBuku()->where('status', 'dipesan')->count();
        $babSelesai = $this->babBuku()->where('status', 'selesai')->count();

        return [
            'total' => $totalBab,
            'tersedia' => $babTersedia,
            'dipesan' => $babDipesan,
            'selesai' => $babSelesai,
            'persen_tersedia' => $totalBab > 0 ? ($babTersedia / $totalBab) * 100 : 0,
            'persen_dipesan' => $totalBab > 0 ? ($babDipesan / $totalBab) * 100 : 0,
            'persen_selesai' => $totalBab > 0 ? ($babSelesai / $totalBab) * 100 : 0,
        ];
    }

    // Method untuk check apakah buku masih aktif dan ada bab tersedia
    public function isAvailable()
    {
        return $this->status === 'aktif' && $this->getBabTersediaAttribute() > 0;
    }

    // Method untuk mendapatkan bab dengan harga terendah - menggunakan harga_per_bab
    public function getBabTermurah()
    {
        return $this->babBuku()->where('status', 'tersedia')->orderBy('nomor_bab', 'asc')->first();
    }

    // Method untuk mendapatkan bab dengan harga tertinggi - menggunakan harga_per_bab
    public function getBabTermahal()
    {
        return $this->babBuku()->where('status', 'tersedia')->orderBy('nomor_bab', 'desc')->first();
    }

    // Method untuk mendapatkan rata-rata harga per bab
    public function getRataHargaPerBab()
    {
        return $this->getHargaPerBab();
    }

    // Method untuk mendapatkan statistik bab berdasarkan status
    public function getStatistikBab()
    {
        return [
            'tersedia' => $this->babBuku()->where('status', 'tersedia')->count(),
            'dipesan' => $this->babBuku()->where('status', 'dipesan')->count(),
            'selesai' => $this->babBuku()->where('status', 'selesai')->count(),
        ];
    }

    // Method untuk mendapatkan bab berdasarkan status
    public function getBabByStatus($status)
    {
        return $this->babBuku()->where('status', $status)->get();
    }

    // Method untuk mendapatkan persentase penyelesaian
    public function getPersentasePenyelesaian()
    {
        $totalBab = $this->total_bab;
        $babSelesai = $this->babBuku()->where('status', 'selesai')->count();
        
        return $totalBab > 0 ? ($babSelesai / $totalBab) * 100 : 0;
    }

    // Method untuk check apakah buku sudah selesai
    public function isCompleted()
    {
        return $this->getPersentasePenyelesaian() >= 100;
    }

    // Method untuk mendapatkan estimasi waktu penyelesaian
    public function getEstimasiWaktuPenyelesaian()
    {
        $babTersedia = $this->getBabTersediaAttribute();
        $babDipesan = $this->babBuku()->where('status', 'dipesan')->count();
        
        // Asumsi rata-rata 1 bab selesai dalam 2 minggu
        $estimasiMinggu = ($babTersedia + $babDipesan) * 2;
        
        return $estimasiMinggu;
    }

    // Method untuk mendapatkan kontributor (penulis yang sudah mengerjakan bab)
    public function getKontributor()
    {
        return $this->pesananBuku()
            ->whereIn('status', ['dipesan', 'selesai'])
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter()
            ->unique('id');
    }

    // Method untuk mendapatkan total penghasilan dari buku - menggunakan pesanan yang selesai
    public function getTotalPenghasilan()
    {
        return $this->pesananBuku()
            ->where('status', 'selesai')
            ->sum('harga') ?? 0;
    }

    // Method untuk mendapatkan sisa budget
    public function getSisaBudget()
    {
        $totalNilai = $this->getTotalNilaiProyekAttribute();
        $sudahDibayar = $this->getTotalPenghasilan();
        
        return $totalNilai - $sudahDibayar;
    }

    // Scope untuk buku yang hampir selesai (>= 80% selesai)
    public function scopeHampirSelesai($query)
    {
        return $query->whereHas('babBuku', function($q) {
            $q->selectRaw('buku_kolaboratif_id, COUNT(*) as total_bab, SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as bab_selesai')
              ->groupBy('buku_kolaboratif_id')
              ->havingRaw('(bab_selesai / total_bab) >= 0.8');
        });
    }

    // Scope untuk buku populer (banyak bab yang sudah dipesan)
    public function scopePopuler($query)
    {
        return $query->whereHas('pesananBuku', function($q) {
            $q->whereIn('status', ['dipesan', 'selesai']);
        }, '>=', 3);
    }

    // Method untuk mendapatkan bab yang paling banyak diminati
    public function getBabTerpopuler()
    {
        return $this->babBuku()
            ->withCount('pesananKolaborasi')
            ->orderBy('pesanan_kolaborasi_count', 'desc')
            ->first();
    }

    // Method untuk mendapatkan deadline terdekat
    public function getDeadlineTerdekat()
    {
        return $this->pesananBuku()
            ->where('status', 'dipesan')
            ->whereNotNull('deadline')
            ->orderBy('deadline', 'asc')
            ->first();
    }

    // Method untuk check apakah ada deadline yang terlewat
    public function hasOverdueDeadline()
    {
        return $this->pesananBuku()
            ->where('status', 'dipesan')
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->exists();
    }

    // Method untuk mendapatkan pesanan yang deadline-nya terlewat
    public function getPesananOverdue()
    {
        return $this->pesananBuku()
            ->where('status', 'dipesan')
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->with('user', 'babBuku')
            ->get();
    }

    // Method untuk mendapatkan progress mingguan
    public function getProgressMingguan($minggu = 4)
    {
        $startDate = now()->subWeeks($minggu);
        
        return $this->pesananBuku()
            ->where('status', 'selesai')
            ->where('updated_at', '>=', $startDate)
            ->selectRaw('WEEK(updated_at) as minggu, COUNT(*) as jumlah_selesai')
            ->groupBy('minggu')
            ->orderBy('minggu')
            ->get();
    }

    // Method untuk export data buku ke array
    public function toExportArray()
    {
        return [
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'kategori' => $this->kategori,
            'target_pembaca' => $this->target_pembaca,
            'total_bab' => $this->total_bab,
            'bab_tersedia' => $this->bab_tersedia,
            'bab_dipesan' => $this->babBuku()->where('status', 'dipesan')->count(),
            'bab_selesai' => $this->babBuku()->where('status', 'selesai')->count(),
            'persentase_selesai' => $this->getPersentasePenyelesaian(),
            'total_nilai_proyek' => $this->total_nilai_proyek,
            'total_penghasilan' => $this->getTotalPenghasilan(),
            'sisa_budget' => $this->getSisaBudget(),
            'harga_per_bab' => $this->getHargaPerBab(),
            'jumlah_kontributor' => $this->getKontributor()->count(),
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    // Method untuk mendapatkan harga per bab
    public function getHargaPerBab()
    {
        return $this->attributes['harga_per_bab'] ?? 0;
    }

    // Method untuk set harga per bab
    public function setHargaPerBab($harga)
    {
        $this->attributes['harga_per_bab'] = $harga;
        return $this;
    }

    // Method untuk mendapatkan total bab yang sudah dikerjakan
    public function getTotalBabDikerjakan()
    {
        return $this->babBuku()->whereIn('status', ['dipesan', 'selesai'])->count();
    }

    // Method untuk mendapatkan persentase bab yang sudah dikerjakan
    public function getPersentaseBabDikerjakan()
    {
        $totalBab = $this->total_bab;
        $babDikerjakan = $this->getTotalBabDikerjakan();
        
        return $totalBab > 0 ? ($babDikerjakan / $totalBab) * 100 : 0;
    }

    // Method untuk check apakah masih ada slot bab tersedia
    public function hasAvailableSlots()
    {
        return $this->getBabTersediaAttribute() > 0;
    }

    // Method untuk mendapatkan estimasi pendapatan total
    public function getEstimasiPendapatanTotal()
    {
        return $this->getHargaPerBab() * $this->total_bab;
    }

    // Method untuk mendapatkan pendapatan yang sudah terealisasi
    public function getPendapatanTerealisasi()
    {
        $babSelesai = $this->babBuku()->where('status', 'selesai')->count();
        return $this->getHargaPerBab() * $babSelesai;
    }

    // Method untuk mendapatkan pendapatan yang sedang dalam proses
    public function getPendapatanDalamProses()
    {
        $babDipesan = $this->babBuku()->where('status', 'dipesan')->count();
        return $this->getHargaPerBab() * $babDipesan;
    }

    // Method untuk mendapatkan potensi pendapatan yang tersisa
    public function getPotensiPendapatanTersisa()
    {
        $babTersedia = $this->getBabTersediaAttribute();
        return $this->getHargaPerBab() * $babTersedia;
    }

    // Method untuk mendapatkan ringkasan finansial
    public function getRingkasanFinansial()
    {
        return [
            'estimasi_total' => $this->getEstimasiPendapatanTotal(),
            'terealisasi' => $this->getPendapatanTerealisasi(),
            'dalam_proses' => $this->getPendapatanDalamProses(),
            'potensi_tersisa' => $this->getPotensiPendapatanTersisa(),
            'persentase_terealisasi' => $this->getEstimasiPendapatanTotal() > 0 ? 
                ($this->getPendapatanTerealisasi() / $this->getEstimasiPendapatanTotal()) * 100 : 0,
        ];
    }

    // Method untuk mendapatkan status kelayakan proyek
    public function getStatusKelayakan()
    {
        $persentaseSelesai = $this->getPersentasePenyelesaian();
        $persentaseDikerjakan = $this->getPersentaseBabDikerjakan();
        
        if ($persentaseSelesai >= 100) {
            return 'selesai';
        } elseif ($persentaseSelesai >= 80) {
            return 'hampir_selesai';
        } elseif ($persentaseDikerjakan >= 50) {
            return 'dalam_progress';
        } elseif ($persentaseDikerjakan > 0) {
            return 'baru_dimulai';
        } else {
            return 'belum_dimulai';
        }
    }

    // Method untuk mendapatkan badge status
    public function getStatusBadge()
    {
        $status = $this->getStatusKelayakan();
        
        $badges = [
            'selesai' => ['class' => 'success', 'text' => 'Selesai'],
            'hampir_selesai' => ['class' => 'info', 'text' => 'Hampir Selesai'],
            'dalam_progress' => ['class' => 'warning', 'text' => 'Dalam Progress'],
            'baru_dimulai' => ['class' => 'primary', 'text' => 'Baru Dimulai'],
            'belum_dimulai' => ['class' => 'secondary', 'text' => 'Belum Dimulai'],
        ];
        
        return $badges[$status] ?? ['class' => 'secondary', 'text' => 'Unknown'];
    }

    // Method untuk mendapatkan rekomendasi aksi
    public function getRekomendasiAksi()
    {
        $status = $this->getStatusKelayakan();
        $babTersedia = $this->getBabTersediaAttribute();
        
        switch ($status) {
            case 'selesai':
                return 'Proyek telah selesai. Siap untuk publikasi.';
            case 'hampir_selesai':
                return 'Proyek hampir selesai. Fokus pada penyelesaian bab terakhir.';
            case 'dalam_progress':
                return 'Proyek berjalan baik. Pantau progress dan deadline.';
            case 'baru_dimulai':
                return 'Proyek baru dimulai. Promosikan untuk menarik lebih banyak penulis.';
            case 'belum_dimulai':
                if ($babTersedia > 0) {
                    return 'Proyek siap dimulai. Promosikan kepada penulis potensial.';
                } else {
                    return 'Buat bab-bab untuk proyek ini terlebih dahulu.';
                }
            default:
                return 'Status tidak diketahui.';
        }
    }

    // Method untuk mendapatkan metrik performa
    public function getMetrikPerforma()
    {
        $totalBab = $this->total_bab;
        $babSelesai = $this->babBuku()->where('status', 'selesai')->count();
        $babDipesan = $this->babBuku()->where('status', 'dipesan')->count();
        $babTersedia = $this->getBabTersediaAttribute();
        
        return [
            'completion_rate' => $totalBab > 0 ? ($babSelesai / $totalBab) * 100 : 0,
            'engagement_rate' => $totalBab > 0 ? (($babSelesai + $babDipesan) / $totalBab) * 100 : 0,
            'availability_rate' => $totalBab > 0 ? ($babTersedia / $totalBab) * 100 : 0,
            'efficiency_score' => $this->calculateEfficiencyScore(),
        ];
    }

    // Method untuk menghitung skor efisiensi
    private function calculateEfficiencyScore()
    {
        $metrik = $this->getMetrikPerforma();
        $completionRate = $metrik['completion_rate'] ?? 0;
        $engagementRate = $metrik['engagement_rate'] ?? 0;
        $daysSinceCreated = $this->created_at->diffInDays(now());
        
        // Skor berdasarkan completion rate (40%), engagement rate (30%), dan kecepatan (30%)
        $completionScore = ($completionRate / 100) * 40;
        $engagementScore = ($engagementRate / 100) * 30;
        $speedScore = $daysSinceCreated > 0 ? min(30, (($completionRate / $daysSinceCreated) * 100)) : 0;
        
        return round($completionScore + $engagementScore + $speedScore, 2);
    }

    // Method untuk mendapatkan trending score
    public function getTrendingScore()
    {
        $recentOrders = $this->pesananBuku()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        $completionRate = $this->getPersentasePenyelesaian();
        $availabilityRate = $this->getBabTersediaAttribute() / max($this->total_bab, 1) * 100;
        
        // Skor trending berdasarkan pesanan baru (50%), completion rate (30%), availability (20%)
        $orderScore = min(50, $recentOrders * 10);
        $completionScore = ($completionRate / 100) * 30;
        $availabilityScore = ($availabilityRate / 100) * 20;
        
        return round($orderScore + $completionScore + $availabilityScore, 2);
    }

    // Scope untuk buku trending
    public function scopeTrending($query, $limit = 10)
    {
        return $query->get()
            ->sortByDesc(function($book) {
                return $book->getTrendingScore();
            })
            ->take($limit);
    }

    // Method untuk format harga
    public function getFormattedHarga($harga = null)
    {
        $amount = $harga ?? $this->getHargaPerBab();
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    // Method untuk mendapatkan estimasi waktu berdasarkan jumlah bab
    public function getEstimasiWaktuBerdasarkanBab()
    {
        $babTersedia = $this->getBabTersediaAttribute();
        
        // Asumsi: 7 hari per bab untuk penulis rata-rata
        $hariPerBab = 7;
        $estimasiHari = $babTersedia * $hariPerBab;
        
        return [
            'hari' => $estimasiHari,
            'minggu' => ceil($estimasiHari / 7),
            'bulan' => ceil($estimasiHari / 30),
        ];
    }

    // Method untuk mendapatkan statistik lengkap
    public function getStatistikLengkap()
    {
        $progress = $this->getProgressAttribute();
        $finansial = $this->getRingkasanFinansial();
        $metrik = $this->getMetrikPerforma();
        
        return [
            'progress' => $progress,
            'finansial' => $finansial,
            'metrik' => $metrik,
            'status_kelayakan' => $this->getStatusKelayakan(),
            'trending_score' => $this->getTrendingScore(),
            'estimasi_waktu' => $this->getEstimasiWaktuBerdasarkanBab(),
            'jumlah_kontributor' => $this->getKontributor()->count(),
            'has_overdue' => $this->hasOverdueDeadline(),
        ];
    }

    // Method untuk check apakah proyek layak dipromosikan
    public function isWorthPromoting()
    {
        $babTersedia = $this->getBabTersediaAttribute();
        $persentaseDikerjakan = $this->getPersentaseBabDikerjakan();
        
        return $this->status === 'aktif' && 
               $babTersedia > 0 && 
               $persentaseDikerjakan < 80; // Masih ada ruang untuk kontributor baru
    }

    // Method untuk mendapatkan daftar bab yang bisa dikerjakan
    public function getBabTersediaList()
    {
        return $this->babBuku()
            ->where('status', 'tersedia')
            ->orderBy('nomor_bab', 'asc')
            ->get();
    }

    // Method untuk mendapatkan kontributor aktif (sedang mengerjakan)
    public function getKontributorAktif()
    {
        return $this->pesananBuku()
            ->where('status', 'dipesan')
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter()
            ->unique('id');
    }

    // Method untuk mendapatkan kontributor yang sudah selesai
    public function getKontributorSelesai()
    {
        return $this->pesananBuku()
            ->where('status', 'selesai')
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter()
            ->unique('id');
    }

    // Method untuk mendapatkan rata-rata waktu penyelesaian per bab
    public function getRataWaktuPenyelesaian()
    {
        $pesananSelesai = $this->pesananBuku()
            ->where('status', 'selesai')
            ->whereNotNull('tanggal_mulai')
            ->whereNotNull('tanggal_selesai')
            ->get();

        if ($pesananSelesai->isEmpty()) {
            return null;
        }

        $totalHari = $pesananSelesai->sum(function($pesanan) {
            return \Carbon\Carbon::parse($pesanan->tanggal_mulai)
                ->diffInDays(\Carbon\Carbon::parse($pesanan->tanggal_selesai));
        });

        return round($totalHari / $pesananSelesai->count(), 1);
    }

    // Method untuk mendapatkan proyeksi penyelesaian
    public function getProyeksiPenyelesaian()
    {
        $babTersedia = $this->getBabTersediaAttribute();
        $babDipesan = $this->babBuku()->where('status', 'dipesan')->count();
        $rataWaktu = $this->getRataWaktuPenyelesaian() ?? 14; // Default 14 hari jika belum ada data

        $estimasiHari = ($babTersedia * $rataWaktu) + ($babDipesan * ($rataWaktu / 2));
        $tanggalProyeksi = now()->addDays($estimasiHari);

        return [
            'estimasi_hari' => ceil($estimasiHari),
            'tanggal_proyeksi' => $tanggalProyeksi,
            'formatted_date' => $tanggalProyeksi->format('d M Y'),
        ];
    }

    // Method untuk check apakah buku dalam kategori prioritas
    public function isPrioritas()
    {
        $persentaseSelesai = $this->getPersentasePenyelesaian();
        $hasOverdue = $this->hasOverdueDeadline();
        $babTersedia = $this->getBabTersediaAttribute();

        return ($persentaseSelesai >= 70 && $babTersedia > 0) || $hasOverdue;
    }

       // Method untuk mendapatkan level prioritas
    public function getLevelPrioritas()
    {
        $persentaseSelesai = $this->getPersentasePenyelesaian();
        $hasOverdue = $this->hasOverdueDeadline();
        $babTersedia = $this->getBabTersediaAttribute();
        $daysSinceCreated = $this->created_at->diffInDays(now());

        if ($hasOverdue) {
            return 'urgent';
        } elseif ($persentaseSelesai >= 80 && $babTersedia > 0) {
            return 'high';
        } elseif ($persentaseSelesai >= 50 || $daysSinceCreated > 30) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    // Method untuk mendapatkan warna badge prioritas
    public function getPrioritasBadge()
    {
        $level = $this->getLevelPrioritas();
        
        $badges = [
            'urgent' => ['class' => 'danger', 'text' => 'Urgent', 'icon' => 'fas fa-exclamation-triangle'],
            'high' => ['class' => 'warning', 'text' => 'Tinggi', 'icon' => 'fas fa-arrow-up'],
            'medium' => ['class' => 'info', 'text' => 'Sedang', 'icon' => 'fas fa-minus'],
            'low' => ['class' => 'secondary', 'text' => 'Rendah', 'icon' => 'fas fa-arrow-down'],
        ];
        
        return $badges[$level] ?? ['class' => 'secondary', 'text' => 'Unknown', 'icon' => 'fas fa-question'];
    }

    // Method untuk mendapatkan notifikasi yang perlu ditampilkan
    public function getNotifikasi()
    {
        $notifikasi = [];
        
        if ($this->hasOverdueDeadline()) {
            $jumlahOverdue = $this->getPesananOverdue()->count();
            $notifikasi[] = [
                'type' => 'danger',
                'message' => "Ada {$jumlahOverdue} bab yang melewati deadline",
                'action' => 'Periksa deadline'
            ];
        }
        
        $persentaseSelesai = $this->getPersentasePenyelesaian();
        if ($persentaseSelesai >= 90 && $this->getBabTersediaAttribute() > 0) {
            $notifikasi[] = [
                'type' => 'success',
                'message' => 'Proyek hampir selesai! Tinggal beberapa bab lagi',
                'action' => 'Promosikan bab tersisa'
            ];
        }
        
        if ($this->getBabTersediaAttribute() == 0 && $persentaseSelesai < 100) {
            $notifikasi[] = [
                'type' => 'warning',
                'message' => 'Semua bab sudah dipesan, tunggu penyelesaian',
                'action' => 'Pantau progress'
            ];
        }
        
        $daysSinceLastActivity = $this->pesananBuku()
            ->latest('updated_at')
            ->first()
            ?->updated_at
            ?->diffInDays(now()) ?? 999;
            
        if ($daysSinceLastActivity > 7 && $this->getBabTersediaAttribute() > 0) {
            $notifikasi[] = [
                'type' => 'info',
                'message' => 'Tidak ada aktivitas selama 7 hari terakhir',
                'action' => 'Tingkatkan promosi'
            ];
        }
        
        return $notifikasi;
    }

    // Method untuk mendapatkan summary singkat untuk dashboard
    public function getDashboardSummary()
    {
        return [
            'judul' => $this->judul,
            'kategori' => $this->kategori,
            'progress' => $this->getPersentasePenyelesaian(),
            'bab_tersedia' => $this->getBabTersediaAttribute(),
            'total_bab' => $this->total_bab,
            'harga_per_bab' => $this->getFormattedHarga(),
            'status_badge' => $this->getStatusBadge(),
            'prioritas_badge' => $this->getPrioritasBadge(),
            'trending_score' => $this->getTrendingScore(),
            'has_notification' => count($this->getNotifikasi()) > 0,
            'notification_count' => count($this->getNotifikasi()),
        ];
    }

    // Method untuk export ke CSV
    public function toCsvArray()
    {
        return [
            $this->id,
            $this->judul,
            $this->kategori,
            $this->target_pembaca,
            $this->total_bab,
            $this->getBabTersediaAttribute(),
            $this->babBuku()->where('status', 'dipesan')->count(),
            $this->babBuku()->where('status', 'selesai')->count(),
            number_format($this->getPersentasePenyelesaian(), 2) . '%',
            $this->getFormattedHarga(),
            $this->getFormattedHarga($this->getTotalNilaiProyekAttribute()),
            $this->getKontributor()->count(),
            $this->status,
            $this->created_at->format('Y-m-d'),
            $this->updated_at->format('Y-m-d'),
        ];
    }

    // Method untuk mendapatkan header CSV
    public static function getCsvHeaders()
    {
        return [
            'ID',
            'Judul',
            'Kategori',
            'Target Pembaca',
            'Total Bab',
            'Bab Tersedia',
            'Bab Dipesan',
            'Bab Selesai',
            'Progress (%)',
            'Harga per Bab',
            'Total Nilai Proyek',
            'Jumlah Kontributor',
            'Status',
            'Tanggal Dibuat',
            'Terakhir Diupdate',
        ];
    }

    // Method untuk validasi data sebelum save
    public function validateData()
    {
        $errors = [];
        
        if (empty($this->judul)) {
            $errors[] = 'Judul tidak boleh kosong';
        }
        
        if (empty($this->deskripsi)) {
            $errors[] = 'Deskripsi tidak boleh kosong';
        }
        
        if ($this->total_bab <= 0) {
            $errors[] = 'Total bab harus lebih dari 0';
        }
        
        if ($this->getHargaPerBab() <= 0) {
            $errors[] = 'Harga per bab harus lebih dari 0';
        }
        
        return $errors;
    }

    // Method untuk auto-update status berdasarkan progress
    public function autoUpdateStatus()
    {
        $persentaseSelesai = $this->getPersentasePenyelesaian();
        
        if ($persentaseSelesai >= 100 && $this->status !== 'selesai') {
            $this->status = 'selesai';
            $this->save();
            return 'Status diubah menjadi selesai';
        }
        
        if ($persentaseSelesai > 0 && $this->status === 'nonaktif') {
            $this->status = 'aktif';
            $this->save();
            return 'Status diubah menjadi aktif';
        }
        
        return null;
    }

    // Method untuk mendapatkan rekomendasi harga berdasarkan kategori
    public function getRekomendasiHarga()
    {
        if (!$this->kategoriBuku) {
            return null;
        }
        
        $avgHarga = static::where('kategori_buku_id', $this->kategori_buku_id)
            ->where('id', '!=', $this->id)
            ->avg('harga_per_bab');
            
        if (!$avgHarga) {
            return null;
        }
        
        return [
            'rata_rata' => $avgHarga,
            'minimum' => $avgHarga * 0.8,
            'maksimum' => $avgHarga * 1.2,
            'formatted_rata_rata' => $this->getFormattedHarga($avgHarga),
            'formatted_minimum' => $this->getFormattedHarga($avgHarga * 0.8),
            'formatted_maksimum' => $this->getFormattedHarga($avgHarga * 1.2),
        ];
    }

    // Method untuk clone buku (untuk template)
    public function cloneAsTemplate($newTitle = null)
    {
        $clone = $this->replicate();
        $clone->judul = $newTitle ?? ($this->judul . ' (Copy)');
        $clone->status = 'nonaktif';
        $clone->gambar_sampul = null;
        $clone->save();
        
        // Clone bab-bab juga jika diperlukan
        foreach ($this->babBuku as $bab) {
            $babClone = $bab->replicate();
            $babClone->buku_kolaboratif_id = $clone->id;
            $babClone->status = 'tersedia';
            $babClone->save();
        }
        
        return $clone;
    }

    // Method untuk mendapatkan statistik performa bulanan
    public function getStatistikBulanan($bulan = 6)
    {
        $startDate = now()->subMonths($bulan);
        
        return $this->pesananBuku()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('
                YEAR(created_at) as tahun,
                MONTH(created_at) as bulan,
                COUNT(*) as total_pesanan,
                SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as pesanan_selesai,
                SUM(CASE WHEN status = "selesai" THEN harga ELSE 0 END) as total_pendapatan
            ')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->map(function($item) {
                $item->nama_bulan = \Carbon\Carbon::create($item->tahun, $item->bulan)->format('M Y');
                $item->completion_rate = $item->total_pesanan > 0 ? 
                    ($item->pesanan_selesai / $item->total_pesanan) * 100 : 0;
                return $item;
            });
    }

    // Method untuk mendapatkan top kontributor
    public function getTopKontributor($limit = 5)
    {
        return $this->pesananBuku()
            ->where('status', 'selesai')
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function($pesanan) {
                $user = $pesanan->first()->user;
                return [
                    'user' => $user,
                    'jumlah_bab' => $pesanan->count(),
                    'total_pendapatan' => $pesanan->sum('harga'),
                    'rata_waktu_penyelesaian' => $pesanan->avg(function($p) {
                        return $p->tanggal_mulai && $p->tanggal_selesai ? 
                            \Carbon\Carbon::parse($p->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($p->tanggal_selesai)) : 0;
                    }),
                ];
            })
            ->sortByDesc('jumlah_bab')
            ->take($limit)
            ->values();
    }

    // Method untuk mendapatkan insight bisnis
    public function getBusinessInsight()
    {
        $totalInvestasi = $this->getTotalNilaiProyekAttribute();
        $pendapatanTerealisasi = $this->getPendapatanTerealisasi();
        $persentaseSelesai = $this->getPersentasePenyelesaian();
        $daysSinceCreated = $this->created_at->diffInDays(now());
        
        $roi = $totalInvestasi > 0 ? (($pendapatanTerealisasi - $totalInvestasi) / $totalInvestasi) * 100 : 0;
        $burnRate = $daysSinceCreated > 0 ? $pendapatanTerealisasi / $daysSinceCreated : 0;
        $estimasiPenyelesaian = $this->getProyeksiPenyelesaian();
        
        return [
            'total_investasi' => $totalInvestasi,
            'pendapatan_terealisasi' => $pendapatanTerealisasi,
            'roi_percentage' => round($roi, 2),
            'burn_rate_per_day' => round($burnRate, 2),
            'completion_velocity' => $daysSinceCreated > 0 ? round($persentaseSelesai / $daysSinceCreated, 2) : 0,
            'estimated_completion' => $estimasiPenyelesaian,
            'profitability_status' => $roi > 0 ? 'profitable' : ($roi < -20 ? 'loss' : 'break_even'),
            'recommendation' => $this->getBusinessRecommendation($roi, $persentaseSelesai, $daysSinceCreated),
        ];
    }

    // Method untuk mendapatkan rekomendasi bisnis
    private function getBusinessRecommendation($roi, $persentaseSelesai, $daysSinceCreated)
    {
        if ($roi < -20 && $persentaseSelesai < 50) {
            return 'Pertimbangkan untuk meninjau ulang strategi atau menghentikan proyek';
        } elseif ($roi > 20 && $persentaseSelesai > 70) {
            return 'Proyek sangat menguntungkan, pertimbangkan untuk membuat proyek serupa';
        } elseif ($daysSinceCreated > 60 && $persentaseSelesai < 30) {
            return 'Proyek berjalan lambat, tingkatkan promosi atau review harga';
        } elseif ($persentaseSelesai > 80) {
            return 'Fokus pada penyelesaian untuk memaksimalkan ROI';
        } else {
            return 'Pantau terus progress dan sesuaikan strategi sesuai kebutuhan';
        }
    }
}
