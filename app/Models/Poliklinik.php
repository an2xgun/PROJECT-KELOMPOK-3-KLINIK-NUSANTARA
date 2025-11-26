<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_poli',
        'kode',
    ];
    public function jadwals()
    {
        return $this->hasMany(JadwalPoli::class);
    }
}
