<x-main-layout :title-page="'Pengembalian Barang'">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Form Pengembalian - {{ $peminjaman->kode_peminjaman }}</h5>
        </div>
        <div class="card-body">
            <x-notif-alert />

            <!-- Info Peminjaman -->
            <div class="alert alert-info">
                <h5 class="mb-3">Informasi Peminjaman</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Kode Peminjaman:</strong> {{ $peminjaman->kode_peminjaman }}</p>
                        <p class="mb-2"><strong>Nama Barang:</strong> {{ $peminjaman->barang->nama_barang }}</p>
                        <p class="mb-2"><strong>Kode Barang:</strong> {{ $peminjaman->barang->kode_barang }}</p>
                        <p class="mb-2"><strong>Jumlah Dipinjam:</strong> {{ $peminjaman->jumlah_dipinjam }} {{ $peminjaman->barang->satuan }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Peminjam:</strong> {{ $peminjaman->nama_peminjam }}</p>
                        <p class="mb-2"><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam->format('d F Y, H:i') }} WIB</p>
                        <p class="mb-2"><strong>Tanggal Kembali (Rencana):</strong> {{ $peminjaman->tanggal_kembali_rencana->format('d F Y, H:i') }} WIB</p>
                        <p class="mb-2"><strong>Kondisi Saat Pinjam:</strong> 
                            <span class="badge bg-info">{{ $peminjaman->kondisi_pinjam }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Warning jika terlambat -->
            @if($peminjaman->status == 'Terlambat')
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Perhatian:</strong> Pengembalian terlambat! 
                Tanggal kembali seharusnya: {{ $peminjaman->tanggal_kembali_rencana->format('d F Y, H:i') }} WIB
                @if($peminjaman->keterlambatan)
                <br><span class="badge bg-danger mt-1">{{ $peminjaman->keterlambatan }}</span>
                @endif
            </div>
            @endif

            <!-- Form Pengembalian -->
            <form action="{{ route('peminjaman.update-kembali', $peminjaman->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kembali_aktual" 
                               class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror" 
                               value="{{ old('tanggal_kembali_aktual', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}"
                               required>
                        @error('tanggal_kembali_aktual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tanggal saat barang dikembalikan</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Pengembalian <span class="text-danger">*</span></label>
                        <input type="time" name="waktu_kembali_aktual" 
                               class="form-control @error('waktu_kembali_aktual') is-invalid @enderror" 
                               value="{{ old('waktu_kembali_aktual', date('H:i')) }}" 
                               required>
                        @error('waktu_kembali_aktual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jam saat barang dikembalikan</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kondisi Barang Saat Kembali <span class="text-danger">*</span></label>
                        <select name="kondisi_kembali" class="form-select @error('kondisi_kembali') is-invalid @enderror" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="Baik" {{ old('kondisi_kembali') == 'Baik' ? 'selected' : '' }}>
                                Baik (Tidak ada kerusakan)
                            </option>
                            <option value="Rusak Ringan" {{ old('kondisi_kembali') == 'Rusak Ringan' ? 'selected' : '' }}>
                                Rusak Ringan (Masih bisa digunakan)
                            </option>
                            <option value="Rusak Berat" {{ old('kondisi_kembali') == 'Rusak Berat' ? 'selected' : '' }}>
                                Rusak Berat (Tidak bisa digunakan)
                            </option>
                        </select>
                        @error('kondisi_kembali')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($peminjaman->status == 'Terlambat')
                    <!-- Wajib diisi jika terlambat -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">
                            Keterangan Pengembalian 
                            <span class="text-danger">* (Wajib karena terlambat)</span>
                        </label>
                        <textarea name="keterangan_kembali" 
                                  class="form-control @error('keterangan_kembali') is-invalid @enderror" 
                                  rows="4" 
                                  placeholder="Jelaskan alasan keterlambatan dan kondisi barang saat dikembalikan..."
                                  required>{{ old('keterangan_kembali') }}</textarea>
                        @error('keterangan_kembali')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-danger">Minimal 10 karakter - Wajib diisi karena pengembalian terlambat</small>
                    </div>
                    @else
                    <!-- Opsional jika tepat waktu -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Keterangan Pengembalian (Opsional)</label>
                        <textarea name="keterangan_kembali" 
                                  class="form-control" 
                                  rows="4" 
                                  placeholder="Catatan tambahan jika diperlukan...">{{ old('keterangan_kembali') }}</textarea>
                        <small class="text-muted">Opsional - Tambahkan catatan jika diperlukan</small>
                    </div>
                    @endif
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Konfirmasi Pengembalian
                    </button>
                    <a href="{{ route('peminjaman.show', $peminjaman->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-main-layout>