<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_name',
        'certificate_image_path',
        'course_id',
        'participant_id',
        'user_id',
        'status',
    ];

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
