<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->enum('sumber_dana', ['Donatur', 'Pemerintah', 'Swadaya'])->after('gambar');
            $table->enum('tipe_barang', ['Individual', 'Kolektif'])->default('Individual')->after('sumber_dana');
            $table->boolean('bisa_dipinjam')->default(true)->after('tipe_barang');
            $table->text('keterangan_kerusakan')->nullable()->after('bisa_dipinjam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['sumber_dana', 'tipe_barang', 'bisa_dipinjam', 'keterangan_kerusakan']);
        });
    }
};