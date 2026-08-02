<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    public const TYPE_PLACEMENT = 'placement';

    public const TYPE_COURSE = 'course';

    /**
     * Batas kelulusan nilai kursus. Angkanya dulu ditulis langsung di penyaring
     * NilaiController dan di beberapa view; disatukan di sini agar tidak
     * bergeser sendiri-sendiri.
     */
    public const NILAI_LULUS = 60;

    protected $fillable = [
        'pendaftaran_id',
        'jenis',
        'listening',
        'speaking',
        'reading',
        'writing',
        'assignment',
        'uktp',
        'ukap',
        'var1',
        'var2',
        'var3',
        'var4',
        'final_score',
        'status',
        'evaluated_by',
        'evaluated_at',
        'keterangan',
    ];

    protected $casts = [
        'evaluated_at' => 'date',
        'final_score' => 'decimal:2',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(Instruktur::class, 'evaluated_by');
    }

    public function scopePlacement($query)
    {
        return $query->where('jenis', self::TYPE_PLACEMENT);
    }

    public function scopeCourse($query)
    {
        return $query->where('jenis', self::TYPE_COURSE);
    }

    public function isLulus(): bool
    {
        return ! is_null($this->final_score) && (float) $this->final_score >= self::NILAI_LULUS;
    }

    /**
     * Rata-rata lima komponen nilai kursus.
     *
     * `array_filter` tanpa callback dulu dipakai di sini dan membuang nilai 0
     * karena 0 dianggap falsy — peserta yang benar-benar mendapat nol jadi
     * tidak ikut dibagi, sehingga rata-ratanya terangkat. Yang boleh diabaikan
     * hanyalah komponen yang memang belum diisi (null).
     */
    public function getAverageScore()
    {
        return self::hitungRataRata([
            $this->listening,
            $this->speaking,
            $this->reading,
            $this->writing,
            $this->assignment,
        ]);
    }

    public static function hitungRataRata(array $komponen): ?float
    {
        $terisi = array_filter($komponen, fn ($nilai) => ! is_null($nilai) && $nilai !== '');

        if (! $terisi) {
            return null;
        }

        return round(array_sum($terisi) / count($terisi), 2);
    }
}
