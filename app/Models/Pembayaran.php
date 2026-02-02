<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id','angsuran_ke',
        'jumlah','status','bukti_path','tgl_bayar'
    ];

    public function pendaftaran() {
        return $this->belongsTo(Pendaftaran::class);
    }
}
