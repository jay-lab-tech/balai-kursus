<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nama_instr', 'spesialisasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kursus tidak menyimpan instruktur_id; penugasan tersimpan di tabel
    // pivot instruktur_kursus_levels, jadi relasinya banyak-ke-banyak.
    public function kursuses()
    {
        return $this->belongsToMany(Kursus::class, 'instruktur_kursus_levels')
            ->withPivot('level_id', 'assigned_at')
            ->withTimestamps();
    }

    // Relasi ke pivot instruktur_kursus_levels
    public function kursusLevels()
    {
        return $this->hasMany(InstrukturKursusLevel::class);
    }
}
