<x-table-list>
    <x-slot name="header">
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
    </x-slot>

    @forelse ($barangs as $index => $barang)
        @php
            // Hitung jumlah yang sedang dipinjam
            $jumlahDipinjam = $barang->peminjaman()
                ->whereIn('status', ['Dipinjam', 'Terlambat'])
                ->sum('jumlah_dipinjam');
        @endphp
        <tr>
            <td>{{ $barangs->firstItem() + $index }}</td>
            <td>
                <strong>{{ $barang->kode_barang }}</strong>
                @if($barang->tipe_barang === 'Kolektif')
                    <br><small class="badge bg-info">Kolektif</small>
                @endif
            </td>
            <td>
                {{ $barang->nama_barang }}
                <br>
                <small class="text-muted">
                    <i class="bi bi-wallet2"></i> {{ $barang->sumber_dana }}
                </small>
            </td>
            <td>{{ $barang->kategori->nama_kategori }}</td>
            <td>{{ $barang->lokasi->nama_lokasi }}</td>
            <td>
                <div>
                    <strong>{{ $barang->jumlah }} {{ $barang->satuan }}</strong>
                    @if($jumlahDipinjam > 0)
                        <br>
                        <small class="text-warning">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $jumlahDipinjam }} {{ $barang->satuan }} sedang dipinjam
                        </small>
                    @endif
                    
                </div>
            </td>
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
                @if($barang->kondisi === 'Rusak Ringan' && $barang->keterangan_kerusakan)
                    <br>
                    <small class="text-muted" title="{{ $barang->keterangan_kerusakan }}">
                        <i class="bi bi-info-circle"></i> {{ Str::limit($barang->keterangan_kerusakan, 30) }}
                    </small>
                @endif
            </td>
            <td>
                @if($barang->isBisaDipinjam())
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Bisa Dipinjam
                    </span>
                @else
                    @if($barang->kondisi === 'Rusak Berat')
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle"></i> Tidak Bisa Dipinjam
                        </span>
                    @elseif($barang->jumlah <= 0)
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-exclamation-triangle"></i> Stok Habis
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            <i class="bi bi-dash-circle"></i> Tidak Tersedia
                        </span>
                    @endif
                @endif
            </td>
            <td class="text-end">
                @can('manage barang')
                    <x-tombol-aksi href="{{ route('barang.show', $barang->id) }}" type="show" />
                    <x-tombol-aksi href="{{ route('barang.edit', $barang->id) }}" type="edit" />
                @endcan

                @can('delete barang')
                    <x-tombol-aksi :href="route('barang.destroy', $barang->id)" type="delete" />
                @endcan
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center">
                <div class="alert alert-danger">
                    Data barang belum tersedia.
                </div>
            </td>
        </tr>
    @endforelse
</x-table-list>