<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','nomor_peserta','no_hp','instansi'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function pendaftarans() {
        return $this->hasMany(Pendaftaran::class);
    }
}
