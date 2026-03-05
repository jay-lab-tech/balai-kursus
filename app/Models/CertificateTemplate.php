<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'kursus_id',
        'name',
        'html_template',
        'signature_path',
        'design_config',
        'is_default',
    ];

    protected $casts = [
        'design_config' => 'array',
        'is_default' => 'boolean',
    ];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }

    /**
     * Get default template (fallback).
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first() 
            ?? self::where('kursus_id', null)->first();
    }

    /**
     * Get template for a specific course, fallback to default.
     */
    public static function forCourse($kursusId)
    {
        return self::where('kursus_id', $kursusId)->first() 
            ?? self::getDefault();
    }
}
