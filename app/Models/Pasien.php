<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $fillable = [
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
        'pekerjaan',
        'wilayah',
        'desa',
        'rujukan_dari',
        'ket_rujukan',
        'tanggal_kunjungan',
        'kode_rm_terakhir',
        'tujuan',
        'jenis_kunjungan',
        'jenis_pembayaran'
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}

