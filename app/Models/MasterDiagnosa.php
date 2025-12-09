<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDiagnosa extends Model
{
    use HasFactory;
    protected $table = 'master_diagnosa';
    protected $fillable = ['kode','nama'];

    public function rekams()
    {
        return $this->belongsToMany(\App\Models\Rekam::class, 'rekam_diagnosa', 'diagnosa_id', 'rekam_id');
    }
}
