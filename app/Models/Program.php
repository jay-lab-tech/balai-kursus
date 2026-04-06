<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'warna'];

    public function kursuses()
    {
        return $this->hasMany(Kursus::class);
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'kursuses', 'program_id', 'level_id')
            ->select('levels.*')
            ->distinct();
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
