<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    
    public function run(): void
    {
        DB::table('barangs')->insert([
            [
                'kode_barang' => 'LP001',
                'nama_barang' => 'Black PC Wired Microphone',
                'kategori_id' => 1,
                'lokasi_id' => 1,
                'jumlah' => 1,
                'satuan'=> 'Unit',
                'kondisi' => 'Baik',
                'tanggal_pengadaan' => '2023-05-15',
                'gambar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
