<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
protected $table = 'pasien';

    protected $fillable = [
        'no_rm',
        'no_rm_lama',
        'nama',
        'nik',
        'no_ihs',
        'agama',
        'pendidikan',
        'status_keluarga',
        'tanggal_lahir',
        'umur_tahun',
        'umur_bulan',
        'umur_hari',
        'jenis_kelamin',
        'gol_darah',
        'alamat',
        'email',
        'no_telp',
        'pekerjaan',
        'wilayah',
        'desa',
        'rujukan_dari',
        'ket_rujukan',
        'tanggal_kunjungan',
        'tujuan',
        'jenis_kunjungan',
        'jenis_pembayaran',
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}

