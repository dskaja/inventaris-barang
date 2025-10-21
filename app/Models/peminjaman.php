<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_peminjaman',
        'barang_id',
        'nama_peminjam',
        'email_peminjam',
        'telepon_peminjam',
        'jumlah_dipinjam',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'kondisi_pinjam',
        'kondisi_kembali',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali_rencana' => 'datetime',
        'tanggal_kembali_aktual' => 'datetime',
    ];

    // Relasi
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Generate kode peminjaman
    public static function generateKode()
    {
        $lastPeminjaman = self::latest('id')->first();
        $number = $lastPeminjaman ? intval(substr($lastPeminjaman->kode_peminjaman, 4)) + 1 : 1;
        return 'PJM' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    protected $table = 'peminjaman';

    // Check apakah terlambat (lebih dari waktu pengembalian)
    public function isTerlambat()
    {
        if ($this->status == 'Dikembalikan') {
            return false;
        }
        
        return Carbon::now()->greaterThan($this->tanggal_kembali_rencana);
    }

    // Hitung durasi peminjaman dalam format yang tepat
    public function getDurasiAttribute()
    {
        $start = $this->tanggal_pinjam;
        $end = $this->tanggal_kembali_rencana;
        
        // Hitung selisih total dalam jam dan menit
        $totalMinutes = $start->diffInMinutes($end);
        $totalHari = floor($totalMinutes / 1440);
        $totalJam = floor(($totalMinutes % 1440) / 60);
        $sisaMenit = $totalMinutes % 60;
        
        // Jika peminjaman kurang dari 1 jam
        if ($totalHari == 0 && $totalJam == 0) {
            return $sisaMenit . ' menit';
        }
        
        // Jika peminjaman di hari yang sama (kurang dari 24 jam)
        if ($totalHari == 0) {
            if ($sisaMenit > 0) {
                return $totalJam . ' jam ' . $sisaMenit . ' menit';
            }
            return $totalJam . ' jam';
        }
        
        // Jika lebih dari 1 hari
        $result = $totalHari . ' hari';
        if ($totalJam > 0) {
            $result .= ' ' . $totalJam . ' jam';
        }
        if ($sisaMenit > 0) {
            $result .= ' ' . $sisaMenit . ' menit';
        }
        
        return $result;
    }

    // Format durasi detail (untuk tampilan lengkap)
    public function getDurasiDetailAttribute()
    {
        $start = $this->tanggal_pinjam;
        $end = $this->tanggal_kembali_rencana;
        
        $totalMinutes = $start->diffInMinutes($end);
        $totalHari = floor($totalMinutes / 1440);
        $totalJam = floor(($totalMinutes % 1440) / 60);
        $sisaMenit = $totalMinutes % 60;
        
        // Jika kurang dari 1 jam
        if ($totalHari == 0 && $totalJam == 0) {
            return $sisaMenit . ' menit';
        }
        
        // Jika kurang dari 1 hari
        if ($totalHari == 0) {
            if ($sisaMenit > 0) {
                return $totalJam . ' jam ' . $sisaMenit . ' menit';
            }
            return $totalJam . ' jam';
        }
        
        // Jika lebih dari 1 hari
        $result = $totalHari . ' hari';
        if ($totalJam > 0) {
            $result .= ' ' . $totalJam . ' jam';
        }
        if ($sisaMenit > 0) {
            $result .= ' ' . $sisaMenit . ' menit';
        }
        
        return $result;
    }

    // Check keterlambatan dengan waktu
    public function getKeterlambatanAttribute()
    {
        if ($this->status == 'Dikembalikan') {
            // Jika sudah dikembalikan, cek apakah terlambat saat pengembalian
            if ($this->tanggal_kembali_aktual->greaterThan($this->tanggal_kembali_rencana)) {
                $totalMinutes = $this->tanggal_kembali_rencana->diffInMinutes($this->tanggal_kembali_aktual);
                $totalHari = floor($totalMinutes / 1440);
                $totalJam = floor(($totalMinutes % 1440) / 60);
                $sisaMenit = $totalMinutes % 60;
                
                if ($totalHari == 0 && $totalJam == 0) {
                    return 'Terlambat ' . $sisaMenit . ' menit';
                }
                
                if ($totalHari == 0) {
                    if ($sisaMenit > 0) {
                        return 'Terlambat ' . $totalJam . ' jam ' . $sisaMenit . ' menit';
                    }
                    return 'Terlambat ' . $totalJam . ' jam';
                }
                
                $result = 'Terlambat ' . $totalHari . ' hari';
                if ($totalJam > 0) {
                    $result .= ' ' . $totalJam . ' jam';
                }
                if ($sisaMenit > 0) {
                    $result .= ' ' . $sisaMenit . ' menit';
                }
                
                return $result;
            }
            return null;
        }
        
        // Jika belum dikembalikan
        if (Carbon::now()->greaterThan($this->tanggal_kembali_rencana)) {
            $totalMinutes = $this->tanggal_kembali_rencana->diffInMinutes(Carbon::now());
            $totalHari = floor($totalMinutes / 1440);
            $totalJam = floor(($totalMinutes % 1440) / 60);
            $sisaMenit = $totalMinutes % 60;
            
            if ($totalHari == 0 && $totalJam == 0) {
                return 'Terlambat ' . $sisaMenit . ' menit';
            }
            
            if ($totalHari == 0) {
                if ($sisaMenit > 0) {
                    return 'Terlambat ' . $totalJam . ' jam ' . $sisaMenit . ' menit';
                }
                return 'Terlambat ' . $totalJam . ' jam';
            }
            
            $result = 'Terlambat ' . $totalHari . ' hari';
            if ($totalJam > 0) {
                $result .= ' ' . $totalJam . ' jam';
            }
            if ($sisaMenit > 0) {
                $result .= ' ' . $sisaMenit . ' menit';
            }
            
            return $result;
        }
        
        return null;
    }
}