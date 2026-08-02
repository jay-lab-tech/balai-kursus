<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'description',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'payment_method',
        'transaction_id',
        'response_data',
        'user_id',
        'pendaftaran_id',
    ];

    protected $casts = [
        'response_data' => 'array',
    ];

    /**
     * Get the user that made the payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pendaftaran that this payment is for
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    /**
     * Scope: Get pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get successful payments
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: Get failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Status dalam bahasa Indonesia untuk ditampilkan.
     *
     * Nilai di kolom status adalah istilah kita sendiri, bukan istilah Midtrans
     * (Midtrans memakai settlement, capture, deny, expire) — jadi tidak ada
     * alasan menuliskannya apa adanya ke layar. Status di luar daftar ini
     * dikembalikan seadanya supaya nilai tak terduga tetap terlihat.
     */
    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'success' => 'Berhasil',
            'pending' => 'Menunggu',
            'failed' => 'Gagal',
            default => (string) $this->status,
        };
    }
}
