<?php
<<<<<<< HEAD

namespace App\Models;

=======
namespace App\Models;
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
<<<<<<< HEAD
    protected $fillable = [
        'jadwalpraktek'
        
    ];
    protected $guarded =['id'];
    public function dokter()
    {

        return $this->hasMany(Dokter::class);
    }
    
=======
    protected $table = 'jadwals';
    protected $fillable = ['jadwalpraktek'];
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
}
