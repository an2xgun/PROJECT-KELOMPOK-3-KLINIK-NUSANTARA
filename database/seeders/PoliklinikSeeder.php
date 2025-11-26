<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poliklinik;

class PoliklinikSeeder extends Seeder
{
    public function run()
    {
        Poliklinik::insert([
            ['nama_poli' => 'Umum', 'kode' => 'U'],
            ['nama_poli' => 'Gigi', 'kode' => 'G'],
            ['nama_poli' => 'Kandungan', 'kode' => 'K'],
        ]);
    }
}
