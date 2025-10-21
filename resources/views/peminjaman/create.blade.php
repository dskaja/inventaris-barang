<x-main-layout :title-page="'Tambah Peminjaman'">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Peminjaman Barang</h5>
        </div>
        <div class="card-body">
            <x-notif-alert />

            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf

                <!-- Pilih Barang -->
                <div class="mb-3">
                    <label class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                    <select name="barang_id" id="barang_id" class="form-select @error('barang_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}" 
                                data-kode="{{ $barang->kode_barang }}"
                                data-nama="{{ $barang->nama_barang ?? $barang->nama }}"
                                data-kategori="{{ $barang->kategori->nama_kategori ?? '-' }}"
                                data-lokasi="{{ $barang->lokasi->nama_lokasi ?? '-' }}"
                                data-tersedia="{{ $barang->jumlah_tersedia }}"
                                {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                            {{ $barang->kode_barang }} - {{ $barang->nama_barang ?? $barang->nama }} (Tersedia: {{ $barang->jumlah_tersedia }})
                        </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Info Barang -->
                <div id="info-barang" class="alert alert-info d-none mb-3">
                    <h6>Informasi Barang:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Kode:</strong> <span id="info-kode">-</span></p>
                            <p class="mb-1"><strong>Nama:</strong> <span id="info-nama">-</span></p>
                            <p class="mb-1"><strong>Kategori:</strong> <span id="info-kategori">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Lokasi:</strong> <span id="info-lokasi">-</span></p>
                            <p class="mb-1"><strong>Stok Tersedia:</strong> <span id="info-tersedia" class="badge bg-success">-</span></p>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Data Peminjam -->
                <h5 class="mb-3">Data Peminjam</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
                        <input type="text" name="nama_peminjam" 
                               class="form-control @error('nama_peminjam') is-invalid @enderror" 
                               value="{{ old('nama_peminjam') }}" required>
                        @error('nama_peminjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email_peminjam" 
                               class="form-control @error('email_peminjam') is-invalid @enderror" 
                               value="{{ old('email_peminjam') }}">
                        @error('email_peminjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="telepon_peminjam" 
                               class="form-control @error('telepon_peminjam') is-invalid @enderror" 
                               value="{{ old('telepon_peminjam') }}">
                        @error('telepon_peminjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <!-- Detail Peminjaman -->
                <h5 class="mb-3">Detail Peminjaman</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jumlah Dipinjam <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_dipinjam" id="jumlah_dipinjam"
                               class="form-control @error('jumlah_dipinjam') is-invalid @enderror" 
                               value="{{ old('jumlah_dipinjam', 1) }}" min="1" required>
                        @error('jumlah_dipinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                               class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                        @error('tanggal_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Pinjam <span class="text-danger">*</span></label>
                        <input type="time" name="waktu_pinjam" 
                               class="form-control @error('waktu_pinjam') is-invalid @enderror" 
                               value="{{ old('waktu_pinjam', date('H:i')) }}" required>
                        @error('waktu_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Kembali (Rencana) <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana"
                               class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                               value="{{ old('tanggal_kembali_rencana') }}" required>
                        @error('tanggal_kembali_rencana')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Kembali (Rencana) <span class="text-danger">*</span></label>
                        <input type="time" name="waktu_kembali_rencana" 
                               class="form-control @error('waktu_kembali_rencana') is-invalid @enderror" 
                               value="{{ old('waktu_kembali_rencana') }}" required>
                        @error('waktu_kembali_rencana')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kondisi Saat Pinjam <span class="text-danger">*</span></label>
                        <select name="kondisi_pinjam" class="form-select @error('kondisi_pinjam') is-invalid @enderror" required>
                            <option value="Baik" {{ old('kondisi_pinjam') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi_pinjam') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        </select>
                        @error('kondisi_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Peminjaman
                    </button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    document.getElementById('barang_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const infoBox = document.getElementById('info-barang');
        
        if (this.value) {
            document.getElementById('info-kode').textContent = selected.dataset.kode;
            document.getElementById('info-nama').textContent = selected.dataset.nama;
            document.getElementById('info-kategori').textContent = selected.dataset.kategori;
            document.getElementById('info-lokasi').textContent = selected.dataset.lokasi;
            document.getElementById('info-tersedia').textContent = selected.dataset.tersedia + ' Unit';
            
            document.getElementById('jumlah_dipinjam').max = selected.dataset.tersedia;
            
            infoBox.classList.remove('d-none');
        } else {
            infoBox.classList.add('d-none');
        }
    });

    // Auto-fill tanggal kembali sama dengan tanggal pinjam jika belum diisi
    document.getElementById('tanggal_pinjam').addEventListener('change', function() {
        const tanggalKembali = document.getElementById('tanggal_kembali_rencana');
        if (!tanggalKembali.value) {
            tanggalKembali.value = this.value;
        }
    });

    if (document.getElementById('barang_id').value) {
        document.getElementById('barang_id').dispatchEvent(new Event('change'));
    }
    </script>
    @endpush
</x-main-layout>