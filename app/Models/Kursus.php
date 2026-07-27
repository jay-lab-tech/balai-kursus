<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'level_id',
        'nama',
        'periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_pelajaran',
        'harga',
        'harga_upi',
        'kuota',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function risalahs()
    {
        return $this->hasMany(Risalah::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function pesertaKursusLevels()
    {
        return $this->hasMany(PesertaKursusLevel::class);
    }

    public function instrukturKursusLevels()
    {
        return $this->hasMany(InstrukturKursusLevel::class);
    }

    public function scopeOpenForRegistration($query)
    {
        return $query->whereIn('status', ['buka', 'berjalan']);
    }
}
