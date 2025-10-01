<x-table-list>
    <x-slot name="header">
        <tr>
            <th>#</th>
            <th><i class="ti ti-map-pin"></i> NAMA LOKASI</th>
            <th><i class="ti ti-package"></i> JUMLAH BARANG</th>
            <th><i class="ti ti-list"></i> DAFTAR BARANG</th>
            @can('manage lokasi')
                <th>AKSI</th>
            @endcan
        </tr>
    </x-slot>

    @forelse ($lokasis as $index => $lokasi)
        <tr>
            <td>{{ $lokasis->firstItem() + $index }}</td>
            <td>
                <i class="ti ti-map-pin text-primary"></i>
                <strong>{{ $lokasi->nama_lokasi }}</strong>
            </td>
            <td>
                @if($lokasi->barang_count > 0)
                    <span class="badge bg-success rounded-pill">{{ $lokasi->barang_count }}</span>
                @else
                    <span class="badge bg-secondary rounded-pill">0</span>
                @endif
            </td>
            <td>
                @if($lokasi->barang_count > 0)
                    <button class="btn btn-sm btn-link text-decoration-none" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#barang-{{ $lokasi->id }}" 
                            aria-expanded="false">
                        <i class="ti ti-eye"></i> Lihat {{ $lokasi->barang_count }} barang
                    </button>
                    
                    <div class="collapse mt-2" id="barang-{{ $lokasi->id }}">
                        <div class="card card-body bg-light">
                            <ul class="list-unstyled mb-0">
                                @foreach($lokasi->barang as $barang)
                                    <li class="mb-1">
                                        <i class="ti ti-box"></i> 
                                        <strong>{{ $barang->kode_barang }}</strong> - {{ $barang->nama_barang }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    <span class="text-muted">
                        <i class="ti ti-inbox-off"></i> Tidak ada barang
                    </span>
                @endif
            </td>

            @can('manage lokasi')
                <td>
                    <x-tombol-aksi :href="route('lokasi.edit', $lokasi->id)" type="edit" />
                    <x-tombol-aksi :href="route('lokasi.destroy', $lokasi->id)" type="delete" />
                </td>
            @endcan
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">
                <div class="alert alert-danger">
                    Data lokasi belum tersedia.
                </div>
            </td>
        </tr>
    @endforelse
</x-table-list>