<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    protected $table = 'obats';
    protected $fillable = [
        'kodeobat',
        'stok',
        'id_jenis',
        'nama',
        'dosis',
        'harga',
        'expired',
        'photo'
    ];

    protected $dates = ['expired'];

    public function jenis()
    {
        return $this->belongsTo(JenisObat::class, 'id_jenis');
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
