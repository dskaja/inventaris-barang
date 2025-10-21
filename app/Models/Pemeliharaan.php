<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pemeliharaan';

    protected $fillable = [
        'kode_pemeliharaan',
        'barang_id',
        'kode_barang',
        'nama_barang',
        'tanggal_masuk',
        'keterangan',
        'biaya_perbaikan',
        'status',
        'tanggal_selesai'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_selesai' => 'date',
        'biaya_perbaikan' => 'decimal:2'
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Generate kode pemeliharaan otomatis
    public static function generateKode()
    {
        $latest = self::latest()->first();
        
        if (!$latest) {
            return 'PM001';
        }

        $lastNumber = intval(substr($latest->kode_pemeliharaan, 2));
        $newNumber = $lastNumber + 1;

        return 'PM' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    // Cek apakah masih dalam perbaikan
    public function isDalamPerbaikan()
    {
        return $this->status === 'Dalam Perbaikan';
    }

    // Cek apakah sudah selesai
    public function isSelesai()
    {
        return $this->status === 'Selesai';
    }
}