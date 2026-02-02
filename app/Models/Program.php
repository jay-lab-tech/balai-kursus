<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function levels() {
        return $this->hasMany(Level::class);
    }

    public function kursuses() {
        return $this->hasMany(Kursus::class);
    }
}
