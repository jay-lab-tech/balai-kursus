<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'risalah_id', 'pendaftaran_id',
        'status', 'jam_datang', 'catatan',
    ];

    /**
     * Kolom status disimpan sebagai enum satu huruf, sedangkan tampilan perlu
     * kata utuh. Peta ini jadi satu-satunya sumber label agar tidak
     * ditebak ulang di tiap view.
     */
    public const LABEL_STATUS = [
        'H' => 'Hadir',
        'S' => 'Sakit',
        'I' => 'Izin',
        'A' => 'Alpa',
    ];

    public function getLabelStatusAttribute(): string
    {
        return self::LABEL_STATUS[$this->status] ?? $this->status;
    }

    public function risalah()
    {
        return $this->belongsTo(Risalah::class);
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
