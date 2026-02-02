<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'risalah_id','pendaftaran_id',
        'status','jam_datang','catatan'
    ];

    public function risalah() {
        return $this->belongsTo(Risalah::class);
    }

    public function pendaftaran() {
        return $this->belongsTo(Pendaftaran::class);
    }
}
