<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'spesialis',   // Umum, Gigi, Kandungan
        'no_izin',
        'telepon',
    ];

    public function jadwal()
    {
        return $this->hasMany(JadwalPoli::class);
    }
}
