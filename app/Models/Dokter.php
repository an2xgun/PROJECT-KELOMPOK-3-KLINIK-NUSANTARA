<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Dokter extends Model
{
    use HasFactory;
<<<<<<< HEAD
    protected $fillable = [
        'nama',
        'alamat',
        'id_poli',
        'telepon',
        'jadwalpraktek'
        
    ];
    protected $guarded =['id'];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwalpraktek', 'id');
    
    }

    public function rekam(){
        return $this->hasMany(Rekam::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
=======
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
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    }
}

