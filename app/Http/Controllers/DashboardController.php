<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Pemeliharaan; // TAMBAHKAN INI
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Update status terlambat dulu sebelum hitung data
        Peminjaman::where('status', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', Carbon::now())
            ->update(['status' => 'Terlambat']);

        $jumlahBarang = Barang::count();
        $jumlahKategori = Kategori::count();
        $jumlahLokasi = Lokasi::count();
        $jumlahUser = User::count();

        // Data Peminjaman
        $jumlahPeminjamanAktif = Peminjaman::whereIn('status', ['Dipinjam', 'Terlambat'])->count();
        $jumlahTerlambat = Peminjaman::where('status', 'Terlambat')->count();

        // Data Pemulihan - TAMBAHKAN INI
        $jumlahPemeliharaan = Pemeliharaan::count();
        // Atau jika ingin hitung yang masih dalam proses:
        // $jumlahPemulihan = Pemulihan::where('status', 'Dalam Perbaikan')->count();

        $kondisiBaik = Barang::where('kondisi', 'Baik')->count();
        $kondisiRusakRingan = Barang::where('kondisi', 'Rusak Ringan')->count();
        $kondisiRusakBerat = Barang::where('kondisi', 'Rusak Berat')->count();

        $barangTerbaru = Barang::with(['kategori', 'lokasi'])->latest()->take(5)->get();

        $peminjamanTerbaru = Peminjaman::with(['barang'])
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'jumlahBarang',
            'jumlahKategori',
            'jumlahLokasi',
            'jumlahUser',
            'jumlahPeminjamanAktif',
            'jumlahTerlambat',
            'jumlahPemeliharaan', // TAMBAHKAN INI
            'kondisiBaik',
            'kondisiRusakRingan',
            'kondisiRusakBerat',
            'barangTerbaru',
            'peminjamanTerbaru'
        ));
    }
}