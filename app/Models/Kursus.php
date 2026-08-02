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

    /**
     * Status kelas yang masih menerima peserta baru. Daftar yang sama dulu
     * ditulis ulang sebagai in_array($kursus->status, ['buka', 'berjalan'])
     * di dalam view program — sehingga scope di bawah dan tampilannya bisa
     * bergeser sendiri-sendiri.
     */
    public const STATUS_MENERIMA = ['buka', 'berjalan'];

    public function scopeOpenForRegistration($query)
    {
        return $query->whereIn('status', self::STATUS_MENERIMA);
    }

    /**
     * Sisa kuota. Butuh pendaftarans_count dari withCount() — kalau tidak ada,
     * relasinya dihitung langsung supaya pemanggil tidak diam-diam salah.
     */
    public function sisaKuota(): int
    {
        $terisi = $this->pendaftarans_count ?? $this->pendaftarans()->count();

        return max(0, (int) $this->kuota - (int) $terisi);
    }

    public function masihMenerima(): bool
    {
        return in_array($this->status, self::STATUS_MENERIMA, true) && $this->sisaKuota() > 0;
    }
}
