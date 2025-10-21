<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeliharaanController extends Controller
{
    public function index()
    {
        $pemeliharaan = Pemeliharaan::with('barang')
            ->orderBy('created_at', 'desc')
            ->get();

        $dalamPerbaikan = Pemeliharaan::where('status', 'Dalam Perbaikan')->count();
        $selesai = Pemeliharaan::where('status', 'Selesai')->count();

        return view('pemeliharaan.index', compact('pemeliharaan', 'dalamPerbaikan', 'selesai'));
    }

    public function create()
    {
        $barangDalamPerbaikan = Pemeliharaan::where('status', 'Dalam Perbaikan')
            ->pluck('barang_id')
            ->toArray();

        // Simple query - sudah cukup
        $barangs = Barang::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])
            ->whereNotIn('id', $barangDalamPerbaikan)
            ->get();

        return view('pemeliharaan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string',
            'biaya_perbaikan' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($request->barang_id);

            $pemeliharaan = Pemeliharaan::create([
                'kode_pemeliharaan' => Pemeliharaan::generateKode(),
                'barang_id' => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'tanggal_masuk' => $request->tanggal_masuk,
                'keterangan' => $request->keterangan,
                'biaya_perbaikan' => $request->biaya_perbaikan,
                'status' => 'Dalam Perbaikan'
            ]);

            // ✅ CUMA UPDATE bisa_dipinjam aja, JANGAN update kondisi!
            $barang->update([
                'bisa_dipinjam' => false
            ]);

            DB::commit();

            return redirect()
                ->route('pemeliharaan.index')
                ->with('success', 'Barang berhasil dimasukkan ke pemeliharaan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pemeliharaan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pemeliharaan = Pemeliharaan::with('barang')->findOrFail($id);
        return view('pemeliharaan.show', compact('pemeliharaan'));
    }

    public function selesai($id)
    {
        DB::beginTransaction();
        try {
            $pemeliharaan = Pemeliharaan::findOrFail($id);
            
            if ($pemeliharaan->status === 'Selesai') {
                return redirect()
                    ->back()
                    ->with('info', 'Pemeliharaan ini sudah ditandai selesai');
            }

            $pemeliharaan->update([
                'status' => 'Selesai',
                'tanggal_selesai' => now()
            ]);

            $barang = Barang::find($pemeliharaan->barang_id);
            if ($barang) {
                // ✅ Baru sekarang kondisi jadi "Baik" setelah selesai diperbaiki
                $barang->update([
                    'kondisi' => 'Baik',
                    'bisa_dipinjam' => true
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pemeliharaan.index')
                ->with('success', 'Pemeliharaan selesai. Barang kembali dalam kondisi baik.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('error', 'Gagal menyelesaikan pemeliharaan: ' . $e->getMessage());
        }
    }


    public function laporan(Request $request)
{
    $query = Pemeliharaan::with('barang');

    // Filter berdasarkan status
    if ($request->status && $request->status !== 'semua') {
        $query->where('status', $request->status);
    }

    // Filter berdasarkan tanggal
    if ($request->tanggal_dari) {
        $query->whereDate('tanggal_masuk', '>=', $request->tanggal_dari);
    }

    if ($request->tanggal_sampai) {
        $query->whereDate('tanggal_masuk', '<=', $request->tanggal_sampai);
    }

    $pemeliharaan = $query->orderBy('tanggal_masuk', 'desc')->get();
    
    $totalBiaya = $pemeliharaan->sum('biaya_perbaikan');
    $dalamPerbaikan = $pemeliharaan->where('status', 'Dalam Perbaikan')->count();
    $selesai = $pemeliharaan->where('status', 'Selesai')->count();

    return view('pemeliharaan.laporan', compact('pemeliharaan', 'totalBiaya', 'dalamPerbaikan', 'selesai', 'request'));
}

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pemeliharaan = Pemeliharaan::findOrFail($id);
            
            // ✅ Kembalikan bisa_dipinjam jadi true, JANGAN ubah kondisi
            if ($pemeliharaan->status === 'Dalam Perbaikan') {
                $barang = Barang::find($pemeliharaan->barang_id);
                if ($barang) {
                    $barang->update([
                        'bisa_dipinjam' => true  // Kembalikan bisa dipinjam
                    ]);
                }
            }

            $pemeliharaan->delete();

            DB::commit();

            return redirect()
                ->route('pemeliharaan.index')
                ->with('success', 'Data pemeliharaan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus pemeliharaan: ' . $e->getMessage());
        }
    }
}