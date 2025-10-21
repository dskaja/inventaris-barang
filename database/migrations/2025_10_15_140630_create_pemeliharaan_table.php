<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pemeliharaan')->unique();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->date('tanggal_masuk');
            $table->text('keterangan')->nullable();
            $table->decimal('biaya_perbaikan', 12, 2)->nullable();
            $table->enum('status', ['Dalam Perbaikan', 'Selesai'])->default('Dalam Perbaikan');
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeliharaan');
    }
};