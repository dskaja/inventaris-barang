<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Middleware untuk izin akses
     */
    public function __construct()
    {
        $this->middleware('permission:view lokasi')->only(['index', 'show']);
        $this->middleware('permission:manage lokasi')->except(['index', 'show']);
    }

    /**
     * Menampilkan daftar lokasi
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;

        $lokasis = Lokasi::withCount('barang')
            ->with('barang:id,kode_barang,nama_barang,lokasi_id')
            ->when($search, function ($query, $search) {
                $query->where('nama_lokasi', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('lokasi.index', compact('lokasis'));
    }

    /**
     * Menampilkan form tambah lokasi
     */
    public function create()
    {
        $lokasi = new Lokasi();
        return view('lokasi.create', compact('lokasi'));
    }

    /**
     * Menyimpan lokasi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasis,nama_lokasi',
        ]);

        Lokasi::create($validated);

        return redirect()->route('lokasi.index')
                ->with('success', 'Lokasi baru berhasil ditambahkan.');
    }

    /**
     * (Nonaktif) Menampilkan satu lokasi (tidak digunakan)
     */
    public function show(Lokasi $lokasi)
    {
        abort(404);
    }

    /**
     * Menampilkan form edit lokasi
     */
    public function edit(Lokasi $lokasi)
    {
        return view('lokasi.edit', compact('lokasi'));
    }

    /**
     * Menyimpan perubahan lokasi
     */
    public function update(Request $request, Lokasi $lokasi)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasis,nama_lokasi,' . $lokasi->id,
        ]);

        $lokasi->update($validated);

        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    /**
     * Menghapus lokasi
     */
    public function destroy(Lokasi $lokasi)
    {
        if ($lokasi->barang()->exists()) {
            return redirect()->route('lokasi.index')
                            ->with('error', 'Lokasi tidak dapat dihapus karena masih memiliki barang terkait.');
        }

        $lokasi->delete();

        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
