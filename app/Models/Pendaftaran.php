<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor',
        'peserta_id',
        'kursus_id',
        'status_pembayaran',
        'total_bayar',
        'terbayar'
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function score()
    {
        return $this->hasOne(Score::class);
    }

    protected static function booted()
    {
        static::created(function ($pendaftaran) {
            // automatically issue certificate when someone registers
            $cert = \App\Models\Certificate::create([
                'peserta_id' => $pendaftaran->peserta_id,
                'kursus_id' => $pendaftaran->kursus_id,
                'issued_at' => now(),
            ]);
            \App\Jobs\GenerateCertificateJob::dispatch($cert);
        });
    }

    public function isLunas()
    {
        return $this->terbayar >= $this->total_bayar;
    }

    public function sisa()
    {
        return $this->total_bayar - $this->terbayar;
    }

    public function progress()
    {
        return round(($this->terbayar / $this->total_bayar) * 100);
    }
}
