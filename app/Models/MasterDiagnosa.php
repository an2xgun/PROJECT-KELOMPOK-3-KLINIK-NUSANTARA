<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDiagnosa extends Model
{
    use HasFactory;
    protected $table = 'master_diagnosa';
    protected $fillable = ['kode','nama'];
}
