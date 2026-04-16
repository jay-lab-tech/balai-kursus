<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'name',
        'institution_name',
        'unit_name',
        'city',
        'header_logo_path',
        'background_image_path',
        'signature_image_path',
        'stamp_image_path',
        'signer_name',
        'signer_title',
        'signer_nip',
        'certificate_prefix',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
