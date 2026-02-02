<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id','nama'
    ];

    public function program() {
        return $this->belongsTo(Program::class);
    }
}
