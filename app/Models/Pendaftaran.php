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

    /**
     * Status disimpan sebagai kode bergaris bawah. Sebelumnya tiap view
     * merapikannya sendiri dengan str_replace('_', ' ', ucfirst(...)) —
     * lima salinan yang menghasilkan "Menunggu tes" tanpa penjelasan apa pun.
     * Peta ini jadi satu-satunya sumber label.
     */
    public const LABEL_STATUS = [
        self::STATUS_MENUNGGU_TES => 'Menunggu tes penempatan',
        self::STATUS_MENUNGGU_PENEMPATAN => 'Menunggu penempatan kelas',
        self::STATUS_MENUNGGU_PEMBAYARAN => 'Menunggu pembayaran',
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_SELESAI => 'Selesai',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    public const LABEL_PEMBAYARAN = [
        self::PAYMENT_PENDING => 'Belum dibayar',
        self::PAYMENT_DP => 'Uang muka',
        self::PAYMENT_CICIL => 'Dicicil',
        self::PAYMENT_LUNAS => 'Lunas',
    ];

    protected $fillable = [
        'nomor',
        'peserta_id',
        'participant_email_snapshot',
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
            if (! $pendaftaran->nomor) {
                $pendaftaran->nomor = 'REG-'.now()->format('Ymd').'-'.strtoupper(substr(uniqid(), -5));
            }

            if (! $pendaftaran->status_pendaftaran) {
                $pendaftaran->status_pendaftaran = self::STATUS_MENUNGGU_TES;
            }

            if (! $pendaftaran->status_pembayaran) {
                $pendaftaran->status_pembayaran = self::PAYMENT_PENDING;
            }

            $pendaftaran->total_bayar ??= 0;
            $pendaftaran->terbayar ??= 0;

            if (! $pendaftaran->participant_email_snapshot && $pendaftaran->peserta_id) {
                $pendaftaran->participant_email_snapshot = Peserta::query()
                    ->join('users', 'users.id', '=', 'pesertas.user_id')
                    ->where('pesertas.id', $pendaftaran->peserta_id)
                    ->value('users.email');
            }

            if (! $pendaftaran->program_id && $pendaftaran->kursus_id) {
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

    public function getLabelStatusAttribute(): string
    {
        return self::LABEL_STATUS[$this->status_pendaftaran] ?? $this->status_pendaftaran;
    }

    public function getLabelPembayaranAttribute(): string
    {
        return self::LABEL_PEMBAYARAN[$this->status_pembayaran] ?? $this->status_pembayaran;
    }

    /**
     * Langkah nyata yang perlu dilakukan peserta pada status ini. Kalimatnya
     * dulu ditulis di dalam rantai @if di dua view yang berbeda dan sudah
     * mulai berbeda bunyi antara keduanya.
     */
    public function getPetunjukAttribute(): string
    {
        return match ($this->status_pendaftaran) {
            self::STATUS_MENUNGGU_TES => 'Ikuti tes penempatan, lalu admin memasukkan hasilnya ke sistem.',
            self::STATUS_MENUNGGU_PENEMPATAN => 'Hasil tes sudah masuk. Kelas yang cocok sedang disiapkan atau kuotanya masih penuh.',
            self::STATUS_MENUNGGU_PEMBAYARAN => 'Kelas sudah ditentukan. Selesaikan pembayaran untuk mulai mengikuti kelas.',
            self::STATUS_AKTIF => 'Pendaftaran aktif. Kelas sudah bisa Anda ikuti.',
            self::STATUS_SELESAI => 'Kursus sudah selesai. Sertifikat dapat diunduh bila sudah diterbitkan.',
            self::STATUS_DIBATALKAN => 'Pendaftaran ini dibatalkan. Hubungi admin bila menurut Anda keliru.',
            default => 'Status pendaftaran: '.$this->status_pendaftaran.'.',
        };
    }

    /**
     * Urutan tahap yang dilalui satu pendaftaran, dipakai untuk menggambar
     * penunjuk alur di ruang peserta. Dibatasi pada jalur normal: dibatalkan
     * bukan tahap lanjutan, jadi tidak punya nomor urut di sini.
     */
    public const ALUR = [
        self::STATUS_MENUNGGU_TES,
        self::STATUS_MENUNGGU_PENEMPATAN,
        self::STATUS_MENUNGGU_PEMBAYARAN,
        self::STATUS_AKTIF,
        self::STATUS_SELESAI,
    ];

    public function urutanAlur(): ?int
    {
        $urutan = array_search($this->status_pendaftaran, self::ALUR, true);

        return $urutan === false ? null : $urutan;
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
        return ! is_null($this->kursus_id);
    }

    public function canBePaid(): bool
    {
        return $this->hasAssignedClass() && (int) $this->total_bayar > 0 && ! $this->isLunas();
    }
}
