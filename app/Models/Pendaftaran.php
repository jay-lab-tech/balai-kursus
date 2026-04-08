<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    public const STATUS_MENUNGGU_TES = 'menunggu_tes';
    public const STATUS_MENUNGGU_PENEMPATAN = 'menunggu_penempatan';
    public const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    public const STATUS_AKTIF = 'aktif';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_DP = 'dp';
    public const PAYMENT_CICIL = 'cicil';
    public const PAYMENT_LUNAS = 'lunas';

    protected $fillable = [
        'nomor',
        'peserta_id',
        'program_id',
        'level_id',
        'kursus_id',
        'status_pendaftaran',
        'status_pembayaran',
        'total_bayar',
        'terbayar',
        'catatan_admin',
        'diklasifikasikan_at',
    ];

    protected $casts = [
        'diklasifikasikan_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $pendaftaran) {
            if (!$pendaftaran->nomor) {
                $pendaftaran->nomor = 'REG-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            }

            if (!$pendaftaran->status_pendaftaran) {
                $pendaftaran->status_pendaftaran = self::STATUS_MENUNGGU_TES;
            }

            if (!$pendaftaran->status_pembayaran) {
                $pendaftaran->status_pembayaran = self::PAYMENT_PENDING;
            }

            $pendaftaran->total_bayar ??= 0;
            $pendaftaran->terbayar ??= 0;

            if (!$pendaftaran->program_id && $pendaftaran->kursus_id) {
                $pendaftaran->program_id = Kursus::query()->whereKey($pendaftaran->kursus_id)->value('program_id');
            }
        });
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
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

    public function placementScore()
    {
        return $this->hasOne(Score::class)->where('jenis', Score::TYPE_PLACEMENT)->latestOfMany();
    }

    public function courseScore()
    {
        return $this->hasOne(Score::class)->where('jenis', Score::TYPE_COURSE)->latestOfMany();
    }

    public function score()
    {
        return $this->courseScore();
    }

    public function isLunas()
    {
        return $this->total_bayar > 0 && $this->terbayar >= $this->total_bayar;
    }

    public function sisa()
    {
        return max(0, (int) $this->total_bayar - (int) $this->terbayar);
    }

    public function progress()
    {
        if ((int) $this->total_bayar <= 0) {
            return 0;
        }

        return (int) round(($this->terbayar / $this->total_bayar) * 100);
    }

    public function hasAssignedClass(): bool
    {
        return !is_null($this->kursus_id);
    }

    public function canBePaid(): bool
    {
        return $this->hasAssignedClass() && (int) $this->total_bayar > 0 && !$this->isLunas();
    }
}
