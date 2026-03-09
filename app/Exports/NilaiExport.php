<?php

namespace App\Exports;

use App\Models\Score;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiExport implements FromCollection, WithHeadings, WithStyles
{
    public $date;
    public function __construct()
    {
        $this->date = date('d-m-Y H:i:s');
    }
    public function collection()
    {
        $data = [];
        // Tambahkan 3 baris kosong untuk judul, subjudul, tanggal
        $data[] = ["BALAI KURSUS UPI", '', '', '', '', '', '', '', '', '', ''];
        $data[] = ["Data Nilai Peserta", '', '', '', '', '', '', '', '', '', ''];
        $data[] = ["Tanggal Export: " . $this->date, '', '', '', '', '', '', '', '', '', ''];
        // Header data
        $data[] = [
            'Peserta',
            'Kursus',
            'Listening',
            'Speaking',
            'Reading',
            'Writing',
            'Assignment',
            'Final Score',
            'Status',
            'Evaluator',
        ];
        // Data nilai
        foreach (Score::with('pendaftaran.peserta.user', 'pendaftaran.kursus', 'evaluator')->get() as $score) {
            $data[] = [
                $score->pendaftaran->peserta->user->name ?? '-',
                $score->pendaftaran->kursus->nama ?? '-',
                $score->listening ?? '-',
                $score->speaking ?? '-',
                $score->reading ?? '-',
                $score->writing ?? '-',
                $score->assignment ?? '-',
                $score->final_score ?? '-',
                $score->status ?? '-',
                $score->evaluator->user->name ?? '-',
            ];
        }
        return collect($data);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Set border for all cells
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        // Border untuk seluruh data
        $sheet->getStyle('A4:' . $highestColumn . $highestRow)
            ->getBorders()->getAllBorders()->setBorderStyle('thin');
        // Auto-size columns
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        // Bold judul dan header
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A4:' . $highestColumn . '4')->getFont()->setBold(true);
        return [];
    }
}
