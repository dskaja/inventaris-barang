<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Sumber Dana</th>
            <th>Jumlah</th>
            <th>Tipe</th>
            <th>Kondisi</th>
            <th>Status</th>
            <th>Tgl. Pengadaan</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($barangs as $index => $barang)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kategori->nama_kategori }}</td>
                <td>{{ $barang->lokasi->nama_lokasi }}</td>
                <td>{{ $barang->sumber_dana }}</td>
                <td>{{ $barang->jumlah }} {{ $barang->satuan }}</td>
                <td>{{ $barang->tipe_barang }}</td>
                <td>
                    {{ $barang->kondisi }}
                    @if($barang->kondisi === 'Rusak Ringan' && $barang->keterangan_kerusakan)
                        <br><small>{{ Str::limit($barang->keterangan_kerusakan, 40) }}</small>
                    @endif
                </td>
                <td>
                    @if($barang->isBisaDipinjam())
                        Bisa Dipinjam
                    @else
                        @if($barang->kondisi === 'Rusak Berat')
                            Rusak Berat
                        @elseif($barang->jumlah <= 0)
                            Stok Habis
                        @else
                            Tidak Tersedia
                        @endif
                    @endif
                </td>
                <td>
                    {{ date('d-m-Y', strtotime($barang->tanggal_pengadaan)) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" style="text-align: center;">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>