<x-main-layout :title-page="'Peminjaman'">
    <div class="card">
        <div class="card-body">
            <!-- Header dengan Search, Filter, dan Tombol -->
            <form action="{{ route('peminjaman.index') }}" method="GET" class="mb-3">
                <div class="row g-2">
                    <!-- Tombol Tambah dan Cetak Laporan -->
                    <div class="col-md-12 mb-2">
                        <div class="d-flex gap-2">
                            <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>
                                Tambah Peminjaman
                            </a>
                            <a href="{{ route('peminjaman.laporan') }}" class="btn btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                                </svg>
                                Cetak Laporan Peminjaman
                            </a>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="col-md-7">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari kode/nama peminjam/barang..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center" style="min-width: 50px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <div class="col-md-5">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>
                </div>
            </form>

            <x-notif-alert class="mt-3"/>

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="3%">#</th>
                            <th width="7%">Kode</th>
                            <th width="15%">Nama Barang</th>
                            <th width="11%">Peminjam</th>
                            <th width="9%">No. Telepon</th>
                            <th width="6%">Jumlah</th>
                            <th width="11%">Tanggal Pinjam</th>
                            <th width="13%">Tanggal Kembali</th>
                            <th width="8%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $index => $p)
                        <tr>
                            <td>{{ $peminjaman->firstItem() + $index }}</td>
                            <td><strong>{{ $p->kode_peminjaman }}</strong></td>
                            <td>
                                <strong>{{ $p->barang->nama_barang ?? $p->barang->nama ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $p->barang->kode_barang }}</small>
                            </td>
                            <td>{{ $p->nama_peminjam }}</td>
                            <td>{{ $p->telepon_peminjam ?? '-' }}</td>
                            <td>{{ $p->jumlah_dipinjam }} Unit</td>
                            <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</strong>
                                @if($p->tanggal_kembali_aktual)
                                    <br><small class="text-success">
                                        Kembali: {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}
                                    </small>
                                @endif
                                @if($p->keterlambatan)
                                    <br><span class="badge bg-danger mt-1">Terlambat</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status == 'Dipinjam')
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                @elseif($p->status == 'Terlambat')
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-success">Dikembalikan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('peminjaman.show', $p->id) }}" 
                                    class="btn btn-info btn-sm" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </a>
                                    
                                    @if($p->status != 'Dikembalikan')
                                    <a href="{{ route('peminjaman.kembalikan', $p->id) }}" 
                                    class="btn btn-success btn-sm" title="Kembalikan">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                            <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                        </svg>
                                    </a>
                                    @endif

                                    @if($p->status == 'Dikembalikan')
                                    <form action="{{ route('peminjaman.destroy', $p->id) }}" 
                                        method="POST" class="d-inline" onsubmit="showDeleteModal(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="display: block; margin: 0 auto 1rem;">
                                    <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                    <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2.002 3h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                                </svg>
                                <p>Tidak ada data peminjaman</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-body">
            {{ $peminjaman->links() }}
        </div>
    </div>

    <!-- Custom Delete Modal -->
    <div id="deleteModal" class="custom-modal" style="display: none;">
        <div class="custom-modal-overlay"></div>
        <div class="custom-modal-content">
            <div class="custom-modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                </svg>
            </div>
            <h3 class="custom-modal-title">Hapus Data Peminjaman?</h3>
            <p class="custom-modal-message">Data yang dihapus tidak dapat dikembalikan. Apakah Anda yakin ingin melanjutkan?</p>
            <div class="custom-modal-buttons">
                <button type="button" class="custom-btn custom-btn-cancel" onclick="closeDeleteModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                    Batal
                </button>
                <button type="button" class="custom-btn custom-btn-delete" onclick="confirmDelete()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                    </svg>
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <style>
    /* Custom Modal Styles */
    .custom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        animation: fadeIn 0.3s ease-out;
    }

    .custom-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }

    .custom-modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border-radius: 20px;
        padding: 40px;
        max-width: 450px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease-out;
    }

    .custom-modal-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        color: white;
        box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);
        animation: scaleIn 0.5s ease-out;
    }

    .custom-modal-icon svg {
        animation: shake 0.5s ease-in-out 0.3s;
    }

    .custom-modal-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
        text-align: center;
    }

    .custom-modal-message {
        font-size: 15px;
        color: #7f8c8d;
        text-align: center;
        margin-bottom: 32px;
        line-height: 1.6;
    }

    .custom-modal-buttons {
        display: flex;
        gap: 12px;
    }

    .custom-btn {
        flex: 1;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .custom-btn-cancel {
        background: #ecf0f1;
        color: #7f8c8d;
    }

    .custom-btn-cancel:hover {
        background: #d5dbdb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .custom-btn-delete {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
    }

    .custom-btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
    }

    .custom-btn:active {
        transform: translateY(0);
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translate(-50%, -40%);
            opacity: 0;
        }
        to {
            transform: translate(-50%, -50%);
            opacity: 1;
        }
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    @keyframes shake {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }

    /* Responsive */
    @media (max-width: 576px) {
        .custom-modal-content {
            padding: 32px 24px;
        }
        
        .custom-modal-buttons {
            flex-direction: column;
        }
        
        .custom-btn {
            width: 100%;
        }
    }
    </style>

    <script>
    let deleteForm = null;

    function showDeleteModal(event) {
        event.preventDefault();
        deleteForm = event.target.closest('form');
        document.getElementById('deleteModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteForm = null;
    }

    function confirmDelete() {
        if (deleteForm) {
            deleteForm.submit();
        }
    }

    // Close modal when clicking overlay
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('custom-modal-overlay')) {
            closeDeleteModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
    </script>
</x-main-layout>