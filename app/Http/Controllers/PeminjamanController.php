<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['barang.kategori', 'barang.lokasi']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhere('nama_peminjam', 'like', "%{$search}%")
                  ->orWhereHas('barang', function($q) use ($search) {
                      $q->where('nama_barang', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Update status terlambat
        $this->updateStatusTerlambat();

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $barangs = Barang::with(['kategori', 'lokasi'])
            ->where('jumlah', '>', 0)
            ->get();
        
        return view('peminjaman.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_peminjam' => 'required|string|max:100',
            'email_peminjam' => 'nullable|email|max:100',
            'telepon_peminjam' => 'nullable|string|max:20',
            'jumlah_dipinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'waktu_pinjam' => 'required|date_format:H:i',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'waktu_kembali_rencana' => 'required|date_format:H:i',
            'kondisi_pinjam' => 'required|in:Baik,Rusak Ringan',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($request->barang_id);

            // Cek stok tersedia (jumlah saat ini di database)
            if ($request->jumlah_dipinjam > $barang->jumlah) {
                return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $barang->jumlah . ' ' . $barang->satuan)->withInput();
            }

            // Gabungkan tanggal dan waktu
            $tanggalPinjam = Carbon::parse($request->tanggal_pinjam . ' ' . $request->waktu_pinjam);
            $tanggalKembaliRencana = Carbon::parse($request->tanggal_kembali_rencana . ' ' . $request->waktu_kembali_rencana);

            // Validasi: waktu kembali harus lebih dari waktu pinjam
            if ($tanggalKembaliRencana->lessThanOrEqualTo($tanggalPinjam)) {
                return back()->with('error', 'Waktu kembali harus lebih dari waktu pinjam!')->withInput();
            }

            // Generate kode peminjaman
            $kodePeminjaman = Peminjaman::generateKode();

            // Tentukan status
            $status = 'Dipinjam';
            if ($tanggalKembaliRencana->isPast()) {
                $status = 'Terlambat';
            }

            // Buat peminjaman
            Peminjaman::create([
                'kode_peminjaman' => $kodePeminjaman,
                'barang_id' => $request->barang_id,
                'nama_peminjam' => $request->nama_peminjam,
                'email_peminjam' => $request->email_peminjam,
                'telepon_peminjam' => $request->telepon_peminjam,
                'jumlah_dipinjam' => $request->jumlah_dipinjam,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_kembali_rencana' => $tanggalKembaliRencana,
                'kondisi_pinjam' => $request->kondisi_pinjam,
                'status' => $status,
                'keterangan' => $request->keterangan
            ]);

            // Kurangi stok barang
            $barang->decrement('jumlah', $request->jumlah_dipinjam);

            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['barang.kategori', 'barang.lokasi'])->findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with(['barang'])->findOrFail($id);
        
        if ($peminjaman->status == 'Dikembalikan') {
            return back()->with('error', 'Barang sudah dikembalikan!');
        }

        return view('peminjaman.kembalikan', compact('peminjaman'));
    }

    public function updateKembali(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Validasi dinamis: keterangan wajib jika terlambat
        $rules = [
            'tanggal_kembali_aktual' => 'required|date',
            'waktu_kembali_aktual' => 'required|date_format:H:i',
            'kondisi_kembali' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
        ];

        if ($peminjaman->status == 'Terlambat') {
            $rules['keterangan_kembali'] = 'required|string|min:10';
        } else {
            $rules['keterangan_kembali'] = 'nullable|string';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            if ($peminjaman->status == 'Dikembalikan') {
                return back()->with('error', 'Barang sudah dikembalikan!');
            }

            // Gabungkan tanggal dan waktu kembali
            $tanggalKembaliAktual = Carbon::parse($request->tanggal_kembali_aktual . ' ' . $request->waktu_kembali_aktual);

            // Update keterangan hanya jika terlambat dan ada keterangan
            $keteranganLengkap = $peminjaman->keterangan;
            
            if ($tanggalKembaliAktual->greaterThan($peminjaman->tanggal_kembali_rencana) && $request->keterangan_kembali) {
                $keteranganLengkap .= "\n\n[PENGEMBALIAN TERLAMBAT]\n" . $request->keterangan_kembali;
            }

            // Update peminjaman
            $peminjaman->update([
                'tanggal_kembali_aktual' => $tanggalKembaliAktual,
                'kondisi_kembali' => $request->kondisi_kembali,
                'status' => 'Dikembalikan',
                'keterangan' => $keteranganLengkap
            ]);

            // Kembalikan stok barang
            $barang = $peminjaman->barang;
            $barang->increment('jumlah', $peminjaman->jumlah_dipinjam);

            // Update kondisi barang jika rusak
            if ($request->kondisi_kembali != 'Baik') {
                $barang->update(['kondisi' => $request->kondisi_kembali]);
            }

            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Barang berhasil dikembalikan!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            if ($peminjaman->status == 'Dipinjam' || $peminjaman->status == 'Terlambat') {
                return back()->with('error', 'Tidak dapat menghapus peminjaman yang masih aktif!');
            }

            $peminjaman->delete();
            return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==================== LAPORAN PEMINJAMAN ====================
    public function laporan(Request $request)
    {
        // Update status terlambat terlebih dahulu SEBELUM query
        $this->updateStatusTerlambat();

        $query = Peminjaman::with(['barang.kategori', 'barang.lokasi']);

        // Filter berdasarkan tanggal pinjam
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan peminjam
        if ($request->filled('peminjam')) {
            $query->where('nama_peminjam', 'like', '%' . $request->peminjam . '%');
        }

        // Get data dengan urutan terbaru
        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();

        // Hitung statistik DARI SEMUA DATA (tanpa filter status)
        $allPeminjaman = Peminjaman::all();
        $stats = [
            'total' => $allPeminjaman->count(),
            'dipinjam' => $allPeminjaman->where('status', 'Dipinjam')->count(),
            'terlambat' => $allPeminjaman->where('status', 'Terlambat')->count(),
            'dikembalikan' => $allPeminjaman->where('status', 'Dikembalikan')->count(),
        ];

        // Ambil parameter filter untuk ditampilkan di view
        $filters = [
            'tanggal_dari' => $request->tanggal_dari,
            'tanggal_sampai' => $request->tanggal_sampai,
            'status' => $request->status,
            'peminjam' => $request->peminjam,
        ];

        return view('peminjaman.laporan', compact('peminjaman', 'stats', 'filters'));
    }

    // Helper: Update status terlambat
    private function updateStatusTerlambat()
    {
        // Update semua peminjaman yang belum dikembalikan dan sudah lewat tanggal kembali
        Peminjaman::where('status', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', Carbon::now())
            ->whereNull('tanggal_kembali_aktual')
            ->update(['status' => 'Terlambat']);
    }

    // API: Get barang info
    public function getBarangInfo($id)
    {
        $barang = Barang::with(['kategori', 'lokasi'])->findOrFail($id);
        
        // Hitung jumlah yang sedang dipinjam
        $jumlahDipinjam = Peminjaman::where('barang_id', $id)
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->sum('jumlah_dipinjam');
        
        // Jumlah total = jumlah tersedia sekarang + yang sedang dipinjam
        $jumlahTotal = $barang->jumlah + $jumlahDipinjam;
        
        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $barang->nama_barang,
                'kode' => $barang->kode_barang,
                'kategori' => $barang->kategori->nama_kategori ?? '-',
                'lokasi' => $barang->lokasi->nama_lokasi ?? '-',
                'jumlah_total' => $jumlahTotal,
                'jumlah_dipinjam' => $jumlahDipinjam,
                'jumlah_tersedia' => $barang->jumlah,
                'kondisi' => $barang->kondisi,
                'satuan' => $barang->satuan
            ]
        ]);
    }
}