<?php
<<<<<<< HEAD

namespace App\Models;

=======
namespace App\Models;
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekam extends Model
{
    use HasFactory;
<<<<<<< HEAD
    protected $fillable = [
        'nomorantrian',
        'id_pasien',
        'Tanggal Periksa',
=======
    protected $table = 'rekams';
    protected $fillable = [
        'laporan',
        'id_pasien',
        'pendaftaran_id',
        'nomorantrian',
        'tanggalperiksa',
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
        'layanan',
        'keluhan',
        'id_dokter',
        'diagnosa',
<<<<<<< HEAD
        'id_obat',
        'jumlahobat',
        'keterangan',
        'lamabaru',
        'rawat',
        'darah',
        'berat',
        'tinggi',
        'pinggang'
    ];
    protected $guarded =['id'];
    protected $dates = ['jadwal_kedatangan'];

    public function pasien(){
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function obat(){
        return $this->belongsTo(Obat::class, 'id_obat');
    }

    public function dokter(){
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }
=======
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
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
}
