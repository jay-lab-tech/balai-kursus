<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Level;
use App\Models\Instruktur;
use App\Models\Pendaftaran;
use App\Models\Risalah;
use App\Models\Absensi;
use App\Models\Peserta;
use App\Models\User;

class Kursus extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id','level_id','instruktur_id',
        'nama','harga','kuota','status'
    ];

    public function program() {
        return $this->belongsTo(Program::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }

    public function instruktur() {
        return $this->belongsTo(Instruktur::class);
    }

    public function pendaftarans() {
        return $this->hasMany(Pendaftaran::class);
    }

    public function risalahs() {
        return $this->hasMany(Risalah::class);
    }
}
