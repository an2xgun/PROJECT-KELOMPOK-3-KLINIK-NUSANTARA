<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pendaftaran extends Model {
use HasFactory;
protected $fillable = [
'pasien_id','poliklinik_id','nomor_antrian','status'
];


public function pasien() { return $this->belongsTo(Pasien::class); }
public function poliklinik() { return $this->belongsTo(Poliklinik::class); }
}
?>