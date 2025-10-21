<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Detail Perbaikan</h2>
    </x-slot>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Detail Perbaikan</h2>
                <a href="{{ route('pemeliharaan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header {{ $pemeliharaan->status === 'Selesai' ? 'bg-success' : 'bg-warning' }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-tools"></i> {{ $pemeliharaan->kode_pemeliharaan }}
                        </h4>
                        @if($pemeliharaan->status === 'Dalam Perbaikan')
                            <span class="badge bg-light text-warning">Dalam Perbaikan</span>
                        @else
                            <span class="badge bg-light text-success">Selesai</span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Info Barang -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-box-seam"></i> Informasi Barang
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Kode Barang</label>
                                <div class="fw-bold">{{ $pemeliharaan->kode_barang }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Nama Barang</label>
                                <div class="fw-bold">{{ $pemeliharaan->nama_barang }}</div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="text-muted small">Kondisi Barang Saat Ini</label>
                                <div>
                                    @if($pemeliharaan->barang)
                                        @php
                                            $kondisi = $pemeliharaan->barang->kondisi;
                                            $badgeClass = match($kondisi) {
                                                'Baik' => 'bg-success',
                                                'Rusak Ringan' => 'bg-warning',
                                                'Rusak Berat' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $kondisi }}</span>
                                    @else
                                        <span class="text-muted">Barang tidak ditemukan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Pemeliharaan -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-calendar-check"></i> Informasi Perbaikan
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Tanggal Masuk</label>
                                <div class="fw-bold">{{ $pemeliharaan->tanggal_masuk->format('d F Y') }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Tanggal Selesai</label>
                                <div class="fw-bold">
                                    @if($pemeliharaan->tanggal_selesai)
                                        {{ $pemeliharaan->tanggal_selesai->format('d F Y') }}
                                    @else
                                        <span class="text-muted">Belum selesai</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="text-muted small">Durasi Perbaikan</label>
                                <div class="fw-bold">
                                    @if($pemeliharaan->tanggal_selesai)
                                        {{ $pemeliharaan->tanggal_masuk->diffInDays($pemeliharaan->tanggal_selesai) }} hari
                                    @else
                                        {{ $pemeliharaan->tanggal_masuk->diffInDays(now()) }} hari (masih dalam perbaikan)
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-file-text"></i> Keterangan Masalah
                        </h5>
                        <div class="bg-light p-3 rounded">
                            @if($pemeliharaan->keterangan)
                                {{ $pemeliharaan->keterangan }}
                            @else
                                <span class="text-muted">Tidak ada keterangan</span>
                            @endif
                        </div>
                    </div>

                    <!-- Biaya -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-cash-coin"></i> Biaya Perbaikan
                        </h5>
                        <div class="alert alert-info mb-0">
                            <h4 class="mb-0">
                                @if($pemeliharaan->biaya_perbaikan)
                                    Rp {{ number_format($pemeliharaan->biaya_perbaikan, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">Tidak ada biaya tercatat</span>
                                @endif
                            </h4>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($pemeliharaan->status === 'Dalam Perbaikan')
                    <div class="d-flex justify-content-end gap-2">
                        <form action="{{ route('pemeliharaan.selesai', $pemeliharaan->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Tandai pemeliharaan ini selesai? Barang akan dikembalikan ke kondisi Baik.')">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Tandai Selesai
                            </button>
                        </form>

                        <form action="{{ route('pemeliharaan.destroy', $pemeliharaan->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus data pemeliharaan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <div class="card-footer text-muted">
                    <small>
                        <i class="bi bi-clock"></i> 
                        Dibuat: {{ $pemeliharaan->created_at->format('d F Y, H:i') }} WIB
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>