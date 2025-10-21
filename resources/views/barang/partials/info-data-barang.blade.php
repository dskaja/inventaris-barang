<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th style="width: 30%;">Kode Barang</th>
            <td>
                <strong>{{ $barang->kode_barang }}</strong>
                @if($barang->tipe_barang === 'Kolektif')
                    <span class="badge bg-info ms-2">Kode Kolektif</span>
                @else
                    <span class="badge bg-secondary ms-2">Kode Individual</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Nama Barang</th>
            <td>{{ $barang->nama_barang }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $barang->kategori->nama_kategori }}</td>
        </tr>
        <tr>
            <th>Lokasi</th>
            <td>{{ $barang->lokasi->nama_lokasi }}</td>
        </tr>
        <tr>
            <th>Sumber Dana</th>
            <td>
                @php
                    $badgeDana = 'bg-primary';
                    if ($barang->sumber_dana == 'Donatur') {
                        $badgeDana = 'bg-success';
                    } elseif ($barang->sumber_dana == 'Pemerintah') {
                        $badgeDana = 'bg-info';
                    } elseif ($barang->sumber_dana == 'Swadaya') {
                        $badgeDana = 'bg-warning text-dark';
                    }
                @endphp
                <span class="badge {{ $badgeDana }}">{{ $barang->sumber_dana }}</span>
            </td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>
                <strong>{{ $barang->jumlah }} {{ $barang->satuan }}</strong>
                @if($barang->jumlah <= 0)
                    <span class="badge bg-danger ms-2">Stok Habis</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Kondisi</th>
            <td>
                @php
                    $badgeClass = 'bg-success';
                    if ($barang->kondisi == 'Rusak Ringan') {
                        $badgeClass = 'bg-warning text-dark';
                    }
                    if ($barang->kondisi == 'Rusak Berat') {
                        $badgeClass = 'bg-danger';
                    }
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $barang->kondisi }}</span>
            </td>
        </tr>
        @if($barang->kondisi === 'Rusak Ringan' && $barang->keterangan_kerusakan)
        <tr>
            <th>Keterangan Kerusakan</th>
            <td>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle"></i>
                    {{ $barang->keterangan_kerusakan }}
                </div>
            </td>
        </tr>
        @endif
        <tr>
            <th>Status Peminjaman</th>
            <td>
                {!! $barang->getStatusPeminjamanBadge() !!}
                @if($barang->bisa_dipinjam && $barang->kondisi !== 'Rusak Berat' && $barang->jumlah > 0)
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-check-circle"></i> Barang ini dapat dipinjam
                    </small>
                @elseif($barang->kondisi === 'Rusak Berat')
                    <small class="text-danger d-block mt-1">
                        <i class="bi bi-x-circle"></i> Barang rusak berat tidak dapat dipinjam
                    </small>
                @elseif($barang->jumlah <= 0)
                    <small class="text-warning d-block mt-1">
                        <i class="bi bi-exclamation-circle"></i> Stok habis, tidak tersedia untuk dipinjam
                    </small>
                @else
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-info-circle"></i> Barang ini tidak tersedia untuk peminjaman
                    </small>
                @endif
            </td>
        </tr>
        <tr>
            <th>Tanggal Pengadaan</th>
            <td>{{ \Carbon\Carbon::parse($barang->tanggal_pengadaan)->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <th>Terakhir Diperbarui</th>
            <td>{{ $barang->updated_at->translatedFormat('d F Y, H:i') }}</td>
        </tr>
    </tbody>
</table>