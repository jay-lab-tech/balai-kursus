<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kursus_id', 'lokasi_id', 'kela_id', 'hari_id', 'pertemuan_ke', 'tgl_pertemuan', 'jam_mulai', 'jam_selesai', 'created_by',
    ];

    protected $casts = [
        'tgl_pertemuan' => 'date',
    ];

    public function scopeConflictingSlot(
        Builder $query,
        int $lokasiId,
        string $tanggalPertemuan,
        string $jamMulai,
        string $jamSelesai,
        ?int $ignoreJadwalId = null
    ): Builder {
        return $query
            ->where('lokasi_id', $lokasiId)
            ->whereDate('tgl_pertemuan', $tanggalPertemuan)
            ->whereNotNull('jam_mulai')
            ->whereNotNull('jam_selesai')
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->when($ignoreJadwalId, fn (Builder $query) => $query->whereKeyNot($ignoreJadwalId));
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function kela()
    {
        return $this->belongsTo(Kela::class);
    }

    public function hari()
    {
        return $this->belongsTo(Hari::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
