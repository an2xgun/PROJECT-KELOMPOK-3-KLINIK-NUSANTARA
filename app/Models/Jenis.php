<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;
    protected $table = 'polikliniks';
    protected $fillable = ['name'];

<<<<<<< HEAD:app/Models/Jenis.php
    protected $fillable = [
        'jenisobat'
    ];

    protected $guarded =['id'];

    public function obat(){
        return $this->hasMany(Pasien::class);
=======
    public function dokter()
    {
        return $this->hasMany(Dokter::class, 'poliklinik_id');
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'poliklinik_id');
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568:app/Models/Poliklinik.php
    }
}
