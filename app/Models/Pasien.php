<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'pasiens';

    protected $fillable = [
        'no_rm',
        'nama',
        'nik',
        'alamat',
        'provinsi',
        'lahir',
        'kelamin',
        'telepon',
        'agama',
        'pendidikan',
        'pekerjaan',
        'golongan_darah',
        'jenis_pasien',
    ];

    /**
     * Generate next no_rm (0001, 0002, etc.)
     */
    public static function generateNextNoRm()
    {
        try {
            // Use DB transaction to ensure atomicity
            return DB::transaction(function () {
                // Get current sequence (lock for update)
                $sequence = DB::table('no_rm_sequences')->lockForUpdate()->first();

                // Find max existing no_rm in pasiens (cast to integer)
                $maxExisting = (int) DB::table('pasiens')->selectRaw('MAX(CAST(no_rm AS UNSIGNED)) as mx')->value('mx');

                if (!$sequence) {
                    // Initialize if not exists; start after existing max
                    $start = $maxExisting > 0 ? $maxExisting + 1 : 1;
                    DB::table('no_rm_sequences')->insert([
                        'next_no_rm' => $start + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $nextNumber = $start;
                } else {
                    $seqNext = (int) $sequence->next_no_rm;
                    // If sequence is behind existing max (e.g. seed inserted higher numbers), advance it
                    if ($seqNext <= $maxExisting) {
                        $nextNumber = $maxExisting + 1;
                        DB::table('no_rm_sequences')->update([
                            'next_no_rm' => $nextNumber + 1,
                            'updated_at' => now(),
                        ]);
                    } else {
                        $nextNumber = $seqNext;
                        DB::table('no_rm_sequences')->update([
                            'next_no_rm' => $seqNext + 1,
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Format as 4-digit number: 0001, 0002, etc.
                $noRm = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                return $noRm;
            });
        } catch (\Exception $e) {
            // Fallback if transaction fails
            \Log::error('No RM generation failed: ' . $e->getMessage());
            return str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Peek next no_rm without advancing the sequence (safe for form display)
     */
    public static function peekNextNoRm()
    {
        try {
            $sequence = DB::table('no_rm_sequences')->first();
            $maxExisting = (int) DB::table('pasiens')->selectRaw('MAX(CAST(no_rm AS UNSIGNED)) as mx')->value('mx');

            if (!$sequence) {
                $next = $maxExisting > 0 ? $maxExisting + 1 : 1;
            } else {
                $next = max((int)$sequence->next_no_rm, $maxExisting + 1);
            }

            return str_pad($next, 4, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            \Log::error('No RM peek failed: ' . $e->getMessage());
            return str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_pasien');
    }

    public function rekam()
    {
        return $this->hasMany(Rekam::class, 'id_pasien');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Accessor for `jenis_kelamin` to keep compatibility with views.
     */
    public function getJenisKelaminAttribute()
    {
        $val = $this->attributes['kelamin'] ?? null;
        if (!$val) return '';

        // common stored values: 'L'/'P' or full words
        $map = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            'LAKI' => 'Laki-laki',
            'PEREMPUAN' => 'Perempuan',
        ];

        $up = strtoupper(trim($val));
        return $map[$up] ?? $val;
    }

    /**
     * Accessor for `umur_tahun` (integer years) computed from `lahir` date.
     */
    public function getUmurTahunAttribute()
    {
        $lahir = $this->attributes['lahir'] ?? null;
        if (!$lahir) return '';

        try {
            return Carbon::parse($lahir)->age;
        } catch (\Exception $e) {
            return '';
        }
    }
}

