<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'urutan',
        'nilai_min',
        'nilai_max',
        'deskripsi',
    ];

    protected $casts = [
        'nilai_min' => 'decimal:2',
        'nilai_max' => 'decimal:2',
    ];

    public function kursuses()
    {
        return $this->hasMany(Kursus::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'kursuses', 'level_id', 'program_id')
            ->select('programs.*')
            ->distinct();
    }

    public function pesertaKursusLevels()
    {
        return $this->hasMany(PesertaKursusLevel::class);
    }

    public function instrukturKursusLevels()
    {
        return $this->hasMany(InstrukturKursusLevel::class);
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    public function matchesScore(float $score): bool
    {
        if ($this->nilai_min !== null && $score < (float) $this->nilai_min) {
            return false;
        }

        if ($this->nilai_max !== null && $score > (float) $this->nilai_max) {
            return false;
        }

        return true;
    }

    public function getRentangNilaiAttribute(): string
    {
        $min = $this->nilai_min !== null ? rtrim(rtrim(number_format((float) $this->nilai_min, 2, '.', ''), '0'), '.') : '0';
        $max = $this->nilai_max !== null ? rtrim(rtrim(number_format((float) $this->nilai_max, 2, '.', ''), '0'), '.') : '100';

        return $min . ' - ' . $max;
    }
}
