<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Tambah Perbaikan</h2>
    </x-slot>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($barangs->isEmpty())
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                Tidak ada barang yang perlu diperbaiki atau semua barang rusak sudah masuk perbaikan.
            </div>
            <a href="{{ route('pemeliharaan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            @else
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Form Tambah Perbaikan</h5>
                        <a href="{{ route('pemeliharaan.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('pemeliharaan.store') }}" method="POST">
                        @csrf

                        <!-- Pilih Barang -->
                        <div class="mb-3">
                            <label for="barang_id" class="form-label">
                                Pilih Barang <span class="text-danger">*</span>
                            </label>
                            <select name="barang_id" id="barang_id" 
                                    class="form-select @error('barang_id') is-invalid @enderror" 
                                    required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" 
                                        data-kode="{{ $barang->kode_barang }}"
                                        data-nama="{{ $barang->nama_barang }}"
                                        data-kondisi="{{ $barang->kondisi }}"
                                        {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                    [{{ $barang->kode_barang }}] {{ $barang->nama_barang }} - {{ $barang->kondisi }}
                                </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hanya menampilkan barang dengan kondisi rusak atau perlu perbaikan</small>
                        </div>

                        <!-- Info Barang Terpilih -->
                        <div id="infoBarang" class="alert alert-light border d-none mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">Kode Barang</small>
                                    <div class="fw-bold" id="showKode">-</div>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Nama Barang</small>
                                    <div class="fw-bold" id="showNama">-</div>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Kondisi Saat Ini</small>
                                    <div><span id="showKondisi" class="badge bg-warning">-</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Masuk -->
                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label">
                                Tanggal Masuk Perbaikan <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="tanggal_masuk" 
                                   id="tanggal_masuk" 
                                   class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                   value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                                   required>
                            @error('tanggal_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">
                                Keterangan Kerusakan/Masalah
                            </label>
                            <textarea name="keterangan" 
                                      id="keterangan" 
                                      rows="4" 
                                      class="form-control @error('keterangan') is-invalid @enderror"
                                      placeholder="Jelaskan masalah atau kerusakan barang...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Biaya Perbaikan -->
                        <div class="mb-3">
                            <label for="biaya_perbaikan" class="form-label">
                                Estimasi Biaya Perbaikan (Opsional)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       name="biaya_perbaikan" 
                                       id="biaya_perbaikan" 
                                       class="form-control @error('biaya_perbaikan') is-invalid @enderror"
                                       placeholder="0"
                                       min="0"
                                       step="1000"
                                       value="{{ old('biaya_perbaikan') }}">
                            </div>
                            @error('biaya_perbaikan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kosongkan jika belum ada estimasi biaya</small>
                        </div>

                        <!-- Info -->
                        <div class="alert alert-info">
                            <small>
                                <strong><i class="bi bi-info-circle"></i> Catatan:</strong><br>
                                • Barang akan otomatis berstatus "Tidak Bisa Dipinjam" selama dalam perbaikan<br>
                                • Setelah perbaikan selesai, barang akan kembali berstatus "Baik" dan "Bisa Dipinjam"
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('pemeliharaan.index') }}" class="btn btn-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Show barang info when selected
document.getElementById('barang_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const infoDiv = document.getElementById('infoBarang');
    
    if (this.value) {
        const kode = selectedOption.dataset.kode || '(kosong)';
        const nama = selectedOption.dataset.nama || '(kosong)';
        const kondisi = selectedOption.dataset.kondisi || '(kosong)';
        
        document.getElementById('showKode').textContent = kode;
        document.getElementById('showNama').textContent = nama;
        
        // Set kondisi dengan warna dinamis
        const kondisiEl = document.getElementById('showKondisi');
        kondisiEl.textContent = kondisi;
        
        // Set warna badge berdasarkan kondisi
        kondisiEl.className = 'badge'; // Reset class
        if (kondisi === 'Rusak Berat') {
            kondisiEl.classList.add('bg-danger'); // Merah
        } else if (kondisi === 'Rusak Ringan') {
            kondisiEl.classList.add('bg-warning'); // Kuning/Orange
        } else if (kondisi === 'Baik') {
            kondisiEl.classList.add('bg-success'); // Hijau
        } else {
            kondisiEl.classList.add('bg-secondary'); // Abu-abu
        }
        
        infoDiv.classList.remove('d-none');
    } else {
        infoDiv.classList.add('d-none');
    }
});
</script>
</x-app-layout>