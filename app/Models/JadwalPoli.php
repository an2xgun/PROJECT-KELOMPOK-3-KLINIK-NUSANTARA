<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPoli extends Model
{
    use HasFactory;

    protected $table = 'jadwal_polis';

    protected $fillable = [
        'poli_id',
        'dokter_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    // Relasi ke poliklinik
    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'poli_id');
    }

    // Relasi ke dokter
    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }
}
