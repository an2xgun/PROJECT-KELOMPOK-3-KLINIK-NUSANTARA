<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekam extends Model
{
    use HasFactory;
    protected $table = 'rekams';
    protected $fillable = [
        'laporan',
        'id_pasien',
        'pendaftaran_id',
        'nomorantrian',
        'tanggalperiksa',
        'layanan',
        'keluhan',
        'id_dokter',
        'diagnosa',
        'diagnosa_primer',
        'diagnosa_sekunder',
        'id_obat',
        'jumlahobat',
        'keterangan',
        'rawat',
        'lamabaru',
        'darah',
        'tinggi',
        'suhu',
        'berat',
        'pinggang',
        'status_pemeriksaan',
        'catatan_status',
        'jadwal_kedatangan',
        'jadwal_selesai'
    ];

    protected $dates = ['tanggalperiksa', 'jadwal_kedatangan', 'jadwal_selesai'];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function tindakan()
    {
        return $this->belongsToMany(MasterTindakan::class, 'rekam_tindakan', 'rekam_id', 'master_tindakan_id');
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
