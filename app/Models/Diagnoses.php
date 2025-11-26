<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- Tambahan penting!

class Diagnoses extends Model
{
    use HasFactory;

    protected $table = 'diagnoses'; // GANTI jika di DB kamu 'diagnosa'
    
    protected $fillable = [
        'code', 'name', 'icd10', 'description'
    ];

    // Jika tabel tidak punya kolom created_at & updated_at
    public $timestamps = false;

    // Accessor untuk ringkasan deskripsi
    public function getShortDescriptionAttribute()
    {
        return Str::limit($this->description, 80);
    }
}
