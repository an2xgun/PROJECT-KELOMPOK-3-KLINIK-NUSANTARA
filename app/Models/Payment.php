<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $fillable = [
        'invoice_id', 'user_id', 'method', 'no_bpjs', 'note', 'amount', 'paid_at'
    ];

    protected $dates = ['paid_at'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
