<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'lokasi_id',
        'jumlah',
        'satuan',
        'kondisi',
        'tanggal_pengadaan',
        'gambar',
        'sumber_dana',
        'tipe_barang',
        'bisa_dipinjam',
        'keterangan_kerusakan'
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
        'bisa_dipinjam' => 'boolean',
    ];

    // ========== TAMBAHKAN 2 ACCESSOR INI ==========
    // Accessor untuk kompatibilitas dengan kode lama yang pakai ->kode
    public function getKodeAttribute()
    {
        return $this->kode_barang;
    }

    // Accessor untuk kompatibilitas dengan kode lama yang pakai ->nama
    public function getNamaAttribute()
    {
        return $this->nama_barang;
    }
    // ========== END TAMBAHAN ==========

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'barang_id');
    }

    // Helper: Get jumlah yang sedang dipinjam
    public function getJumlahDipinjamAttribute()
    {
        return $this->peminjaman()
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->sum('jumlah_dipinjam');
    }

    // Helper: Get jumlah tersedia (stok yang bisa dipinjam)
    public function getJumlahTersediaAttribute()
    {
        return $this->jumlah;
    }

    // Helper: Get jumlah total (tersedia + dipinjam)
    public function getJumlahTotalAttribute()
    {
        return $this->jumlah + $this->jumlah_dipinjam;
    }

    // Helper: Cek apakah stok tersedia
    public function isStokTersedia($jumlah = 1)
    {
        return $this->jumlah >= $jumlah;
    }

    // Helper: Cek apakah barang bisa dipinjam
    public function isBisaDipinjam()
    {
        // Tidak bisa dipinjam jika:
        // 1. Status bisa_dipinjam = false
        // 2. Kondisi rusak berat
        // 3. Stok habis (jumlah <= 0)
        if (!$this->bisa_dipinjam || $this->kondisi === 'Rusak Berat' || $this->jumlah <= 0) {
            return false;
        }
        
        return true;
    }

    // Helper: Get status badge untuk bisa dipinjam
    public function getStatusPeminjamanBadge()
    {
        if (!$this->isBisaDipinjam()) {
            if ($this->kondisi === 'Rusak Berat') {
                return '<span class="badge bg-danger">Rusak Berat - Tidak Tersedia</span>';
            } elseif ($this->jumlah <= 0) {
                return '<span class="badge bg-warning">Stok Habis</span>';
            } else {
                return '<span class="badge bg-secondary">Tidak Bisa Dipinjam</span>';
            }
        }
        
        return '<span class="badge bg-success">Bisa Dipinjam</span>';
    }

    // Boot method untuk auto-set bisa_dipinjam berdasarkan kondisi
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($barang) {
            // Auto set bisa_dipinjam = false jika kondisi Rusak Berat
            if ($barang->kondisi === 'Rusak Berat') {
                $barang->bisa_dipinjam = false;
            }
        });
    }
}