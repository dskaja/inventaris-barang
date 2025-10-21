<table class="table table-hover">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Peminjam</th>
            <th>Tgl. Kembali</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($peminjamanTerbaru as $p)
        <tr>
            <td><strong>{{ $p->kode_peminjaman }}</strong></td>
            <td>{{ $p->barang->nama_barang }}</td>
            <td>{{ $p->nama_peminjam }}</td>
            <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
            <td>
                <span class="badge {{ $p->status == 'Terlambat' ? 'bg-danger' : 'bg-warning text-dark' }}">
                    {{ $p->status }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center text-muted">Tidak ada peminjaman aktif</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($peminjamanTerbaru->count() > 0)
<div class="text-center mt-3">
    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-primary">
        Lihat Semua Peminjaman
    </a>
</div>
@endif