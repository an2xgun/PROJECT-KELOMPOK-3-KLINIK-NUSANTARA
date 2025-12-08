<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    use HasFactory;
    protected $table = 'polikliniks';
    protected $fillable = ['name'];

    public function dokter()
    {
        return $this->hasMany(Dokter::class, 'poliklinik_id');
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'poliklinik_id');
    }
}
