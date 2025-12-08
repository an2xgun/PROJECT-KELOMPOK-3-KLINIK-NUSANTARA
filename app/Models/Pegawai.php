<?php
<<<<<<< HEAD

namespace App\Models;

=======
namespace App\Models;
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
<<<<<<< HEAD

=======
    protected $table = 'pegawais';
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    protected $fillable = [
        'kodepegawai',
        'nama',
        'alamat',
        'kelamin',
        'telepon',
        'agama',
        'jabatan'
    ];
}
