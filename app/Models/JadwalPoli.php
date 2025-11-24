<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPoli extends Model
{
    use HasFactory;
    protected $table = 'jadwal_polis';
    protected $fillable = ['poliklinik_id','dokter_id','hari','jam_mulai','jam_selesai'];

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }
}
