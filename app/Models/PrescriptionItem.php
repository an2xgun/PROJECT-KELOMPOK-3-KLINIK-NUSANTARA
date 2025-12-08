<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;
    protected $table = 'prescription_items';
    protected $fillable = [
        'prescription_id',
        'obat_id',
        'dosis',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
