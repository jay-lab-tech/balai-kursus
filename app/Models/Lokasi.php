<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasis';

    protected $fillable = [
        'nama',
        'alamat',
        'no_telp',
        'kota',
        'provinsi',
        'keterangan'
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
