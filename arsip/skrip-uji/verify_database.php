<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\{Kursus, Jadwal, Pendaftaran, Score, Lokasi, Kela, Hari, Instruktur, Peserta};

echo "\n========================================\n";
echo "DATABASE VERIFICATION REPORT\n";
echo "========================================\n\n";

// 1. Master Data
echo "✓ MASTER DATA:\n";
echo "  - Lokasi: " . Lokasi::count() . " records\n";
echo "  - Kelas: " . Kela::count() . " records\n";
echo "  - Hari: " . Hari::count() . " records\n";
echo "  - Program: " . \App\Models\Program::count() . " records\n";
echo "  - Level: " . \App\Models\Level::count() . " records\n";
echo "  - Instruktur: " . Instruktur::count() . " records\n";
echo "  - Peserta: " . Peserta::count() . " records\n\n";

// 2. Kursus Relationships
echo "✓ KURSUS RELATIONSHIPS:\n";
$kursus = Kursus::with('program', 'level', 'instruktur', 'instruktur2')->first();
if ($kursus) {
    echo "  Sample: " . $kursus->nama . "\n";
    echo "  - Program: " . ($kursus->program?->nama ?? 'ERROR') . "\n";
    echo "  - Level: " . ($kursus->level?->nama ?? 'ERROR') . "\n";
    echo "  - Instruktur 1: " . ($kursus->instruktur?->nama_instr ?? 'ERROR') . "\n";
    echo "  - Instruktur 2: " . ($kursus->instruktur2?->nama_instr ?? 'NONE') . "\n";
    echo "  - Periode: " . ($kursus->periode ?? 'NONE') . "\n";
    echo "  - Harga UPI: " . ($kursus->harga_upi ?? 'NONE') . "\n";
} else {
    echo "  ERROR: No kursus found\n";
}
echo "\n";

// 3. Jadwal Relationships
echo "✓ JADWAL RELATIONSHIPS:\n";
$jadwal = Jadwal::with('kursus', 'lokasi', 'kela', 'hari')->first();
if ($jadwal) {
    echo "  Sample Jadwal: Pertemuan " . $jadwal->pertemuan_ke . "\n";
    echo "  - Kursus: " . ($jadwal->kursus?->nama ?? 'ERROR') . "\n";
    echo "  - Lokasi: " . ($jadwal->lokasi?->nama ?? 'ERROR') . "\n";
    echo "  - Kelas: " . ($jadwal->kela?->nama ?? 'ERROR') . "\n";
    echo "  - Hari: " . ($jadwal->hari?->nama ?? 'ERROR') . "\n";
    echo "  - Tanggal: " . $jadwal->tgl_pertemuan->format('d/m/Y') . "\n";
} else {
    echo "  ERROR: No jadwal found\n";
}
echo "\n";

// 4. Pendaftaran Relationships
echo "✓ PENDAFTARAN RELATIONSHIPS:\n";
$pend = Pendaftaran::with('peserta', 'kursus', 'pembayarans', 'scores')->first();
if ($pend) {
    echo "  Sample: #" . $pend->nomor . "\n";
    echo "  - Peserta: " . ($pend->peserta?->nomor_peserta ?? 'ERROR') . "\n";
    echo "  - Kursus: " . ($pend->kursus?->nama ?? 'ERROR') . "\n";
    echo "  - Status Bayar: " . $pend->status_pembayaran . "\n";
    echo "  - Total: Rp " . number_format($pend->total_bayar) . "\n";
    echo "  - Terbayar: Rp " . number_format($pend->terbayar) . "\n";
    echo "  - Pembayaran: " . $pend->pembayarans->count() . " records\n";
    echo "  - Scores: " . $pend->scores->count() . " records\n";
} else {
    echo "  ERROR: No pendaftaran found\n";
}
echo "\n";

// 5. Score Relationships
echo "✓ SCORE RELATIONSHIPS:\n";
$score = Score::with('pendaftaran.peserta', 'pendaftaran.kursus', 'evaluator')->first();
if ($score) {
    echo "  Sample Score:\n";
    echo "  - Peserta: " . ($score->pendaftaran?->peserta?->nomor_peserta ?? 'ERROR') . "\n";
    echo "  - Kursus: " . ($score->pendaftaran?->kursus?->nama ?? 'ERROR') . "\n";
    echo "  - Evaluator: " . ($score->evaluator?->nama_instr ?? 'ERROR') . "\n";
    echo "  - Listening: " . $score->listening . "\n";
    echo "  - Speaking: " . $score->speaking . "\n";
    echo "  - Reading: " . $score->reading . "\n";
    echo "  - Writing: " . $score->writing . "\n";
    echo "  - Assignment: " . $score->assignment . "\n";
    echo "  - Final Score: " . $score->final_score . "\n";
    echo "  - UKTP: " . ($score->uktp ?? 'NONE') . "\n";
    echo "  - UKAP: " . ($score->ukap ?? 'NONE') . "\n";
    echo "  - Status: " . $score->status . "\n";
} else {
    echo "  ERROR: No score found\n";
}
echo "\n";

// 6. Check Indexes
echo "✓ DATABASE INDEXES (Applied):\n";
$tables = ['pendaftarans', 'jadwals', 'scores', 'kursuses'];
foreach($tables as $table) {
    $indexes = \DB::select("SHOW INDEX FROM $table WHERE Key_name != 'PRIMARY'");
    echo "  - $table: " . count($indexes) . " indexes\n";
}
echo "\n";

echo "========================================\n";
echo "✅ VERIFICATION COMPLETE\n";
echo "========================================\n\n";
