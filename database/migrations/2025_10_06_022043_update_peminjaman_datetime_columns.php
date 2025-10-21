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
        Schema::table('peminjaman', function (Blueprint $table) {
            // Ubah kolom date menjadi datetime
            $table->dateTime('tanggal_pinjam')->change();
            $table->dateTime('tanggal_kembali_rencana')->change();
            $table->dateTime('tanggal_kembali_aktual')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Kembalikan ke date jika rollback
            $table->date('tanggal_pinjam')->change();
            $table->date('tanggal_kembali_rencana')->change();
            $table->date('tanggal_kembali_aktual')->nullable()->change();
        });
    }
};