<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REVOKED = 'revoked';

    protected $fillable = [
        'template_id',
        'certificate_name',
        'certificate_number',
        'serial_number',
        'issued_date',
        'certificate_image_path',
        'pdf_path',
        'course_id',
        'participant_id',
        'user_id',
        'status',
        'participant_name_snapshot',
        'program_name_snapshot',
        'course_name_snapshot',
        'hours_snapshot',
        'start_date_snapshot',
        'end_date_snapshot',
        'signer_name_snapshot',
        'signer_title_snapshot',
        'signer_nip_snapshot',
        'city_snapshot',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'start_date_snapshot' => 'date',
        'end_date_snapshot' => 'date',
    ];

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }

    public function course()
    {
        return $this->belongsTo(Kursus::class, 'course_id');
    }

    public function participant()
    {
        return $this->belongsTo(Peserta::class, 'participant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
