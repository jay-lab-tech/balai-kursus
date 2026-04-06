<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiExport implements FromCollection, WithHeadings, WithStyles
{
    public string $date;

    public function __construct()
    {
        $this->date = date('d-m-Y H:i:s');
    }

    public function collection()
    {
        $data = [];

        $data[] = ['BALAI KURSUS UPI', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['Data Hasil Tes Penempatan', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['Tanggal Export: ' . $this->date, '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = [
            'Nomor',
            'Peserta',
            'Program',
            'Listening',
            'Speaking',
            'Reading',
            'Writing',
            'Assignment',
            'Final Score',
            'Level Hasil',
            'Kelas Hasil',
            'Evaluator',
        ];

        $pendaftarans = Pendaftaran::with(['peserta.user', 'program', 'level', 'kursus', 'placementScore.evaluator.user'])
            ->whereNotNull('program_id')
            ->get();

        foreach ($pendaftarans as $pendaftaran) {
            $score = $pendaftaran->placementScore;

            $data[] = [
                $pendaftaran->nomor ?? '-',
                $pendaftaran->peserta->user->name ?? '-',
                $pendaftaran->program->nama ?? '-',
                $score->listening ?? '-',
                $score->speaking ?? '-',
                $score->reading ?? '-',
                $score->writing ?? '-',
                $score->assignment ?? '-',
                $score->final_score ?? '-',
                $pendaftaran->level->nama ?? '-',
                $pendaftaran->kursus->nama ?? '-',
                $score->evaluator->user->name ?? '-',
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle('A4:' . $highestColumn . $highestRow)
            ->getBorders()->getAllBorders()->setBorderStyle('thin');

        foreach (range('A', $highestColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A4:' . $highestColumn . '4')->getFont()->setBold(true);

        return [];
    }
}
