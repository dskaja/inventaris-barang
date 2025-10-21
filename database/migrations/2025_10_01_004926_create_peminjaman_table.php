<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman', 20)->unique();
            
            // PERBAIKAN: Ganti 'barang' jadi 'barangs'
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            
            $table->string('nama_peminjam', 100);
            $table->string('email_peminjam', 100)->nullable();
            $table->string('telepon_peminjam', 20)->nullable();
            $table->integer('jumlah_dipinjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->enum('kondisi_pinjam', ['Baik', 'Rusak Ringan'])->default('Baik');
            $table->enum('kondisi_kembali', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();
            $table->enum('status', ['Dipinjam', 'Dikembalikan', 'Terlambat'])->default('Dipinjam');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};