<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage barang')->except('destroy');
        $this->middleware('permission:delete barang')->only('destroy');
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $barangs = Barang::with(['kategori', 'lokasi'])
            ->when($search, function ($query, $search) {
                $query->where('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $lokasi   = Lokasi::all();
        $barang   = new Barang();

        return view('barang.create', compact('barang', 'kategori', 'lokasi'));
    }

    public function store(Request $request)
    {
        // Validasi dasar - HAPUS validasi boolean untuk bisa_dipinjam
        $validated = $request->validate([
            'kode_barang'       => 'required|string|max:50',
            'nama_barang'       => 'required|string|max:150',
            'kategori_id'       => 'required|exists:kategoris,id',
            'lokasi_id'         => 'required|exists:lokasis,id',
            'jumlah'            => 'required|integer|min:1',
            'satuan'            => 'required|string|max:20',
            'kondisi'           => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tanggal_pengadaan' => 'required|date',
            'gambar'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sumber_dana'       => 'required|in:Donatur,Pemerintah,Swadaya',
            'tipe_barang'       => 'required|in:Individual,Kolektif',
            // HAPUS validasi boolean ini
            // 'bisa_dipinjam'     => 'nullable|boolean',
            'keterangan_kerusakan' => 'required_if:kondisi,Rusak Ringan|nullable|string|max:500',
        ], [
            'keterangan_kerusakan.required_if' => 'Keterangan kerusakan wajib diisi untuk barang dengan kondisi Rusak Ringan.',
        ]);

        // MANUAL handling untuk bisa_dipinjam
        // Auto-set bisa_dipinjam berdasarkan kondisi
        if ($validated['kondisi'] === 'Rusak Berat') {
            $validated['bisa_dipinjam'] = false;
        } else {
            // Jika checkbox dicentang, request akan punya key 'bisa_dipinjam'
            // Jika tidak dicentang, key tidak ada
            $validated['bisa_dipinjam'] = $request->has('bisa_dipinjam');
        }

        // Hapus keterangan_kerusakan jika kondisi Baik
        if ($validated['kondisi'] === 'Baik') {
            $validated['keterangan_kerusakan'] = null;
        }

        // Handle upload gambar
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store(null, 'gambar-barang');
        }

        try {
            DB::beginTransaction();

            if ($validated['tipe_barang'] === 'Individual') {
                // TIPE INDIVIDUAL: Generate multiple records dengan kode berurutan
                $kodeBarang = trim($validated['kode_barang']);
                $jumlah = $validated['jumlah'];
                
                // Extract prefix dan nomor dari kode
                // Contoh: GK001 -> prefix: GK, nomor: 001
                preg_match('/^([A-Za-z]+)(\d+)$/', $kodeBarang, $matches);
                
                if (count($matches) !== 3) {
                    throw new \Exception('Format kode barang tidak valid. Gunakan format seperti: GK001, MJ001, KB001.');
                }

                $prefix = $matches[1]; // GK
                $startNumber = (int)$matches[2]; // 001
                $digitLength = strlen($matches[2]); // 3 digit

                // Cek apakah ada kode yang sudah digunakan
                $existingCodes = [];
                
                for ($i = 0; $i < $jumlah; $i++) {
                    $currentNumber = $startNumber + $i;
                    $currentCode = $prefix . str_pad($currentNumber, $digitLength, '0', STR_PAD_LEFT);
                    
                    if (Barang::where('kode_barang', $currentCode)->exists()) {
                        $existingCodes[] = $currentCode;
                    }
                }

                if (!empty($existingCodes)) {
                    throw new \Exception('Kode barang sudah digunakan: ' . implode(', ', $existingCodes));
                }

                // Generate multiple records
                $barangsToInsert = [];
                for ($i = 0; $i < $jumlah; $i++) {
                    $currentNumber = $startNumber + $i;
                    $currentCode = $prefix . str_pad($currentNumber, $digitLength, '0', STR_PAD_LEFT);
                    
                    $barangsToInsert[] = [
                        'kode_barang' => $currentCode,
                        'nama_barang' => $validated['nama_barang'],
                        'kategori_id' => $validated['kategori_id'],
                        'lokasi_id' => $validated['lokasi_id'],
                        'jumlah' => 1, // Individual selalu 1 unit per record
                        'satuan' => $validated['satuan'],
                        'kondisi' => $validated['kondisi'],
                        'tanggal_pengadaan' => $validated['tanggal_pengadaan'],
                        'gambar' => $gambarPath,
                        'sumber_dana' => $validated['sumber_dana'],
                        'tipe_barang' => $validated['tipe_barang'],
                        'bisa_dipinjam' => $validated['bisa_dipinjam'],
                        'keterangan_kerusakan' => $validated['keterangan_kerusakan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert
                Barang::insert($barangsToInsert);
                
                $endCode = $prefix . str_pad($startNumber + $jumlah - 1, $digitLength, '0', STR_PAD_LEFT);
                $message = "✅ Berhasil menambahkan {$jumlah} unit barang individual dengan kode {$kodeBarang} sampai {$endCode}";

            } else {
                // TIPE KOLEKTIF: Simpan 1 record saja dengan jumlah sesuai input
                
                // Cek apakah kode sudah digunakan
                if (Barang::where('kode_barang', $validated['kode_barang'])->exists()) {
                    throw new \Exception('Kode barang ' . $validated['kode_barang'] . ' sudah digunakan.');
                }

                $validated['gambar'] = $gambarPath;
                Barang::create($validated);
                
                $message = "✅ Data barang kolektif berhasil ditambahkan.";
            }

            DB::commit();
            
            return redirect()->route('barang.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus gambar jika ada error
            if ($gambarPath) {
                Storage::disk('gambar-barang')->delete($gambarPath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'lokasi']);
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        $lokasi   = Lokasi::all();

        return view('barang.edit', compact('barang', 'kategori', 'lokasi'));
    }

    public function update(Request $request, Barang $barang)
    {
        // Validasi tanpa boolean validation untuk bisa_dipinjam
        $validated = $request->validate([
            'kode_barang'       => 'required|string|max:50|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang'       => 'required|string|max:150',
            'kategori_id'       => 'required|exists:kategoris,id',
            'lokasi_id'         => 'required|exists:lokasis,id',
            'jumlah'            => 'required|integer|min:0',
            'satuan'            => 'required|string|max:20',
            'kondisi'           => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tanggal_pengadaan' => 'required|date',
            'gambar'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sumber_dana'       => 'required|in:Donatur,Pemerintah,Swadaya',
            'tipe_barang'       => 'required|in:Individual,Kolektif',
            'keterangan_kerusakan' => 'required_if:kondisi,Rusak Ringan|nullable|string|max:500',
        ], [
            'keterangan_kerusakan.required_if' => 'Keterangan kerusakan wajib diisi untuk barang dengan kondisi Rusak Ringan.',
        ]);

        // Manual handling untuk bisa_dipinjam
        if ($validated['kondisi'] === 'Rusak Berat') {
            $validated['bisa_dipinjam'] = false;
        } else {
            $validated['bisa_dipinjam'] = $request->has('bisa_dipinjam');
        }

        // Hapus keterangan_kerusakan jika kondisi bukan Rusak Ringan
        if ($validated['kondisi'] !== 'Rusak Ringan') {
            $validated['keterangan_kerusakan'] = null;
        }

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('gambar-barang')->delete($barang->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store(null, 'gambar-barang');
        }

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->gambar) {
            Storage::disk('gambar-barang')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus.');
    }

    public function cetakLaporan()
    {
        $barang = Barang::with(['kategori', 'lokasi'])->get();

        $data = [
            'title'   => 'Laporan Data Barang Inventaris',
            'date'    => date('d F Y'),
            'barangs' => $barang,
        ];

        $pdf = Pdf::loadView('barang.laporan', $data);
        return $pdf->stream('laporan-inventaris-barang.pdf');
    }
}