<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Manajemen Perbaikan</h2>
    </x-slot>

<div class="container-fluid py-4">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">
                                <i class="bi bi-tools"></i> Dalam Perbaikan
                            </h6>
                            <h2 class="mb-0">{{ $dalamPerbaikan }}</h2>
                        </div>
                        <div class="display-4 text-warning opacity-25">
                            <i class="bi bi-wrench"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">
                                <i class="bi bi-check-circle"></i> Selesai
                            </h6>
                            <h2 class="mb-0">{{ $selesai }}</h2>
                        </div>
                        <div class="display-4 text-success opacity-25">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Tombol Aksi -->
                <div class="d-flex gap-2">
                    <a href="{{ route('pemeliharaan.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Perbaikan
                    </a>
                    <a href="{{ route('pemeliharaan.laporan') }}" class="btn btn-success btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                                </svg> Cetak Laporan Perbaikan
                    </a>
                </div>
                
                <!-- Search Box -->
                <input type="text" id="searchInput" class="form-control form-control-sm" 
                placeholder="Cari kode/nama barang..." style="width: 300px;">
            </div>
        </div>

        <div class="card-body">
            @if($pemeliharaan->isEmpty())
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> Belum ada data Perbaikan
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="pemeliharaanTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="12%">Kode Perbaikan</th>
                            <th width="12%">Barang</th>
                            <th width="15%">Keterangan</th>
                            <th width="12%">Biaya</th>
                            <th width="10%">Status</th>
                            <th width="10%">Tanggal Masuk</th>
                            <th width="10%">Tanggal Selesai</th>
                            <th width="14%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pemeliharaan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->kode_pemeliharaan }}</strong>
                            </td>
                            <td>
                                <strong>{{ $item->nama_barang }}</strong><br>
                                <small class="text-muted">{{ $item->kode_barang }}</small>
                            </td>
                            <td>
                                <small>{{ Str::limit($item->keterangan ?? '-', 50) }}</small>
                            </td>
                            <td>
                                @if($item->biaya_perbaikan)
                                    Rp {{ number_format($item->biaya_perbaikan, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status === 'Dalam Perbaikan')
                                    <span class="badge bg-warning">Dalam Perbaikan</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                            <td>
                                @if($item->tanggal_selesai)
                                    {{ $item->tanggal_selesai->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <!-- Detail -->
                                    <a href="{{ route('pemeliharaan.show', $item->id) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($item->status === 'Dalam Perbaikan')
                                    <!-- Selesai -->
                                    <form action="{{ route('pemeliharaan.selesai', $item->id) }}" 
                                          method="POST" 
                                          class="d-inline confirm-selesai">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm btn-success" 
                                                title="Tandai Selesai">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Hapus -->
                                    <form action="{{ route('pemeliharaan.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="d-inline confirm-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- CSS UNTUK POPUP CONFIRMATION -->
<style>
/* Custom Confirm Dialog */
.swal2-popup {
    border-radius: 15px !important;
    padding: 2rem !important;
}

.swal2-title {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
    color: #2c3e50 !important;
}

.swal2-html-container {
    font-size: 1rem !important;
    color: #5a6c7d !important;
    margin: 1.5rem 0 !important;
}

.swal2-confirm {
    background-color: #3085d6 !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 0.75rem 2rem !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
}

.swal2-confirm:hover {
    background-color: #2779c9 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(48, 133, 214, 0.4) !important;
}

.swal2-cancel {
    background-color: #6c757d !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 0.75rem 2rem !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
}

.swal2-cancel:hover {
    background-color: #5a6268 !important;
    transform: translateY(-2px);
}

.swal2-icon.swal2-warning {
    border-color: #f39c12 !important;
    color: #f39c12 !important;
}

/* Tombol aksi spacing */
.gap-1 {
    gap: 0.375rem !important;
}
</style>

<!-- SCRIPT UNTUK POPUP KEREN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#pemeliharaanTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

// Custom confirmation untuk SELESAI
document.querySelectorAll('.confirm-selesai').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Tandai pemeliharaan ini selesai?',
            text: 'Barang akan dikembalikan ke kondisi Baik',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

// Custom confirmation untuk HAPUS
document.querySelectorAll('.confirm-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data pemeliharaan ini akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
</x-app-layout>