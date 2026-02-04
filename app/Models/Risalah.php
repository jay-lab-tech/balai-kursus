<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risalah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kursus_id','instruktur_id','jadwal_id',
        'pertemuan_ke','tgl_pertemuan','materi','catatan'
    ];

    protected $casts = [
        'tgl_pertemuan' => 'date',
    ];

    public function kursus() {
        return $this->belongsTo(Kursus::class);
    }

    public function instruktur() {
        return $this->belongsTo(Instruktur::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function absensis() {
        return $this->hasMany(Absensi::class);
    }
}
