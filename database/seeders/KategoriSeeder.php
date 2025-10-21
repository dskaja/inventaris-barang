<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            ['nama_kategori' => 'Alat Musik', 'created_at' =>now(), 'updated_at' => now()],
            ['nama_kategori' => 'Elektronik', 'created_at' =>now(), 'updated_at' => now()],
            ['nama_kategori' => 'Fasilitas Umum', 'created_at' =>now(), 'updated_at' => now()],
        ]);
    }
}
