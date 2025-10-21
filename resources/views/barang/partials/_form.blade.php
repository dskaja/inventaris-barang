@csrf
<div class="row mb-3">
    <div class="col-md-6">
        <x-form-input label="Kode Barang" name="kode_barang" :value="$barang->kode_barang" />
        <small class="text-muted">Gunakan kode unik untuk barang individual, atau kode kolektif untuk barang sejenis</small>
    </div>
    <div class="col-md-6">
        <x-form-input label="Nama Barang" name="nama_barang" :value="$barang->nama_barang" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <x-form-select label="Kategori" name="kategori_id" :value="$barang->kategori_id"
            :option-data="$kategori" option-label="nama_kategori" option-value="id" />
    </div>
    <div class="col-md-6">
        <x-form-select label="Lokasi" name="lokasi_id" :value="$barang->lokasi_id"
            :option-data="$lokasi" option-label="nama_lokasi" option-value="id" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <x-form-input label="Jumlah" name="jumlah" :value="$barang->jumlah" type="number" />
    </div>
    <div class="col-md-4">
        <x-form-input label="Satuan" name="satuan" :value="$barang->satuan" />
    </div>
    <div class="col-md-4">
        <label class="form-label">Tipe Barang <span class="text-danger">*</span></label>
        <select name="tipe_barang" class="form-select @error('tipe_barang') is-invalid @enderror" required>
            <option value="">-- Pilih Tipe --</option>
            <option value="Individual" {{ old('tipe_barang', $barang->tipe_barang) == 'Individual' ? 'selected' : '' }}>
                Individual (Kode Unik Per Unit)
            </option>
            <option value="Kolektif" {{ old('tipe_barang', $barang->tipe_barang) == 'Kolektif' ? 'selected' : '' }}>
                Kolektif (Satu Kode untuk Banyak)
            </option>
        </select>
        @error('tipe_barang')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Individual: Komputer, Printer, Meja. Kolektif: Spidol, Buku, Alat Tulis</small>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        @php
            $kondisi = [
                ['kondisi' => 'Baik'],
                ['kondisi' => 'Rusak Ringan'],
                ['kondisi' => 'Rusak Berat'],
            ];
        @endphp

        <x-form-select label="Kondisi" name="kondisi" :value="$barang->kondisi"
            :option-data="$kondisi" option-label="kondisi" option-value="kondisi" id="kondisi-select" />
    </div>
    <div class="col-md-6">
        @php
            $tanggal = $barang->tanggal_pengadaan
                ? date('Y-m-d', strtotime($barang->tanggal_pengadaan))
                : null;
        @endphp

        <x-form-input label="Tanggal Pengadaan" name="tanggal_pengadaan" type="date" :value="$tanggal" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Sumber Dana <span class="text-danger">*</span></label>
        <select name="sumber_dana" class="form-select @error('sumber_dana') is-invalid @enderror" required>
            <option value="">-- Pilih Sumber Dana --</option>
            <option value="Donatur" {{ old('sumber_dana', $barang->sumber_dana) == 'Donatur' ? 'selected' : '' }}>
                Donatur
            </option>
            <option value="Pemerintah" {{ old('sumber_dana', $barang->sumber_dana) == 'Pemerintah' ? 'selected' : '' }}>
                Pemerintah
            </option>
            <option value="Swadaya" {{ old('sumber_dana', $barang->sumber_dana) == 'Swadaya' ? 'selected' : '' }}>
                Swadaya
            </option>
        </select>
        @error('sumber_dana')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Status Peminjaman</label>
        <div class="form-check form-switch" style="padding-top: 8px;">
            <input class="form-check-input" type="checkbox" name="bisa_dipinjam" id="bisa_dipinjam" 
                {{ old('bisa_dipinjam', $barang->bisa_dipinjam ?? true) ? 'checked' : '' }}
                {{ old('kondisi', $barang->kondisi) === 'Rusak Berat' ? 'disabled' : '' }}>
            <label class="form-check-label" for="bisa_dipinjam">
                Barang Bisa Dipinjam
            </label>
        </div>
        <small class="text-muted">Otomatis nonaktif jika kondisi Rusak Berat</small>
    </div>
</div>

<div class="mb-3" id="keterangan-kerusakan-wrapper" style="display: {{ old('kondisi', $barang->kondisi) === 'Rusak Ringan' ? 'block' : 'none' }};">
    <label class="form-label">Keterangan Kerusakan <span class="text-danger">*</span></label>
    <textarea name="keterangan_kerusakan" 
              class="form-control @error('keterangan_kerusakan') is-invalid @enderror" 
              rows="3" 
              placeholder="Contoh: rusak di tombol power, layar pecah sedikit, kabel agak longgar">{{ old('keterangan_kerusakan', $barang->keterangan_kerusakan) }}</textarea>
    @error('keterangan_kerusakan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Wajib diisi jika kondisi barang Rusak Ringan</small>
</div>

<div class="mb-3">
    <x-form-input label="Gambar Barang" name="gambar" type="file" />
    @if($barang->gambar)
        <small class="text-muted">Gambar saat ini: {{ $barang->gambar }}</small>
    @endif
</div>

<div class="mt-4">
    <x-primary-button>
        {{ isset($update) ? __('Update') : __('Simpan') }}
    </x-primary-button>

    <x-tombol-kembali href="{{ route('barang.index') }}" />
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kondisiSelect = document.getElementById('kondisi-select');
    const bisaDipinjamCheckbox = document.getElementById('bisa_dipinjam');
    const keteranganWrapper = document.getElementById('keterangan-kerusakan-wrapper');
    const keteranganTextarea = document.querySelector('textarea[name="keterangan_kerusakan"]');

    function handleKondisiChange() {
        const kondisi = kondisiSelect.value;
        
        if (kondisi === 'Rusak Berat') {
            bisaDipinjamCheckbox.checked = false;
            bisaDipinjamCheckbox.disabled = true;
            keteranganWrapper.style.display = 'none';
            keteranganTextarea.removeAttribute('required');
        } else if (kondisi === 'Rusak Ringan') {
            bisaDipinjamCheckbox.disabled = false;
            keteranganWrapper.style.display = 'block';
            keteranganTextarea.setAttribute('required', 'required');
        } else {
            bisaDipinjamCheckbox.disabled = false;
            keteranganWrapper.style.display = 'none';
            keteranganTextarea.removeAttribute('required');
        }
    }

    kondisiSelect.addEventListener('change', handleKondisiChange);
    handleKondisiChange(); // Run on page load
});
</script>
@endpush