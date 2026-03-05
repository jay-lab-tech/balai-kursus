<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($cert) {
            if (empty($cert->no_sertifikat)) {
                $count = Certificate::count() + 1;
                $cert->no_sertifikat = sprintf('BK-%d-%05d', now()->year, $count);
            }
            if (empty($cert->verification_code)) {
                $cert->verification_code = bin2hex(random_bytes(6));
            }
        });
    }

    protected $fillable = [
        'no_sertifikat',
        'peserta_id',
        'kursus_id',
        'template_id',
        'issued_at',
        'generated_at',
        'file_path',
        'verification_code',
        'status',
        'meta',
        'revoked_reason',
        'revoked_at',
        'revoked_by',
        'expires_at',
        'validity_days',
        'signature_type',
        'signature_path',
    ];

    protected $casts = [
        'meta' => 'array',
        'issued_at' => 'datetime',
        'generated_at' => 'datetime',
        'revoked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class, 'kursus_id');
    }

    public function revokedBy()
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }

    /**
     * Check if certificate is still valid (not expired).
     */
    public function isValid()
    {
        if ($this->status === 'revoked') {
            return false;
        }
        if ($this->expires_at && now()->isAfter($this->expires_at)) {
            return false;
        }
        return true;
    }

    /**
     * Get expiry status (active/expired/none).
     */
    public function getExpiryStatus()
    {
        if (!$this->expires_at) {
            return 'none';
        }
        return now()->isAfter($this->expires_at) ? 'expired' : 'active';
    }

    /**
     * Days until expiry (positive = active, negative = expired, null = no expiry).
     */
    public function daysUntilExpiry()
    {
        if (!$this->expires_at) {
            return null;
        }
        return now()->diffInDays($this->expires_at, false);
    }

    /**
     * Apply (Terbitkan) certificate - change status to 'applied' and send email.
     */
    public function apply()
    {
        $this->update(['status' => 'applied']);
        
        // Send email to peserta
        \App\Jobs\SendCertificateEmail::dispatch($this);
        
        return true;
    }

    /**
     * Reject certificate - change status to 'rejected' with optional reason.
     */
    public function reject($reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'revoked_reason' => $reason,
        ]);
        
        return true;
    }
}
