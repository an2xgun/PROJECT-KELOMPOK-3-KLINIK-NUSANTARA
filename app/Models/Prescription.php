<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    protected $table = 'prescriptions';
    protected $fillable = [
        'rekam_id',
        'dokter_id',
        'status'
    ];

    public function rekam()
    {
        return $this->belongsTo(Rekam::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
