<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JadwalPoli;

class Pendaftaran extends Model
{
    use HasFactory;
    protected $table = 'pendaftaran';
    protected $fillable = [
        'pasien_id',
        'poliklinik_id',
        'jadwal_poli_id',
        'nomor_antrian',
        'keluhan',
        'no_bpjs',
        'jenis_pembayaran',
        'tanggal_kunjungan',
        'status',
        'status_layanan'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'poliklinik_id');
    }

    public function jadwalPoli()
    {
        return $this->belongsTo(JadwalPoli::class, 'jadwal_poli_id');
    }

    public function rekam()
    {
        return $this->hasMany(Rekam::class, 'pendaftaran_id');
    }
}
