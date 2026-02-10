<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'listening',
        'speaking',
        'reading',
        'writing',
        'assignment',
        'uktp',
        'ukap',
        'var1',
        'var2',
        'var3',
        'var4',
        'final_score',
        'status',
        'evaluated_by',
        'evaluated_at',
        'keterangan'
    ];

    protected $casts = [
        'evaluated_at' => 'date',
        'final_score' => 'decimal:2',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(Instruktur::class, 'evaluated_by');
    }

    public function getAverageScore()
    {
        $scores = array_filter([
            $this->listening,
            $this->speaking,
            $this->reading,
            $this->writing,
            $this->assignment
        ]);
        
        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }
}
