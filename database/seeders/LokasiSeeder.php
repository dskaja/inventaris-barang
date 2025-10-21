<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lokasis')->insert([
            [ 
            'nama_lokasi' => 'Sanggar Seni',
            'created_at' => now(),
            'updated_at' => now()
        ],
        
    ]);
    }
}
