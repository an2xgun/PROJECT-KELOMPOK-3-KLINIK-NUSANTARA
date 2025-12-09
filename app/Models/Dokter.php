<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'dokters';
    protected $fillable = ['nama', 'alamat', 'poliklinik_id', 'telepon', 'jadwalpraktek'];

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'poliklinik_id');
    }

    public function rekam()
    {
        return $this->hasMany(Rekam::class, 'id_dokter');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
