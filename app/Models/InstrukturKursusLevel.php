<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrukturKursusLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'instruktur_id', 'kursus_id', 'level_id', 'assigned_at'
    ];

    public function instruktur() {
        return $this->belongsTo(Instruktur::class);
    }
    public function kursus() {
        return $this->belongsTo(Kursus::class);
    }
    public function level() {
        return $this->belongsTo(Level::class);
    }
}
