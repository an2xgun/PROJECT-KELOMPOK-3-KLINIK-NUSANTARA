<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $fillable = [
        'rekam_id',
        'pasien_id',
        'layanan',
        'jenis_pembayaran',
        'no_bpjs',
        'keterangan_pembayaran',
        'subtotal',
        'total',
        'status',
        'paid_at'
    ];

    protected $dates = ['paid_at'];

    public function rekam()
    {
        return $this->belongsTo(Rekam::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
