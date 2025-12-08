<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisObat extends Model
{
    use HasFactory;
    protected $table = 'jenis';
    protected $fillable = ['jenisobat'];

    public function obat()
    {
        return $this->hasMany(Obat::class, 'id_jenis');
    }
}
