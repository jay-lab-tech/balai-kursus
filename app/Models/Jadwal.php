<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kursus_id', 'pertemuan_ke', 'tgl_pertemuan', 'jam_mulai', 'jam_selesai', 'created_by'
    ];

    protected $casts = [
        'tgl_pertemuan' => 'date',
    ];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
