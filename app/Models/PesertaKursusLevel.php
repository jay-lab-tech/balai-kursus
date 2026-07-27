<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaKursusLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'peserta_id', 'kursus_id', 'level_id', 'assigned_at',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
