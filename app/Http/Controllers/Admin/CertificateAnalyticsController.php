<?php

namespace App\Http\Controllers\Admin;

use App\Models\Certificate;
use App\Models\Kursus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CertificateAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show analytics dashboard.
     */
    public function index(Request $request)
    {
        $period = $request->query('period', '30days'); // 30days, 90days, 1year, all
        $startDate = $this->getStartDate($period);

        // Overall stats
        $query = Certificate::query();
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        $total = $query->count();
        $generated = $query->clone()->where('status', 'generated')->count();
        $revoked = $query->clone()->where('status', 'revoked')->count();
        $queued = $query->clone()->where('status', 'queued')->count();

        // By course
        $byCourse = Kursus::with(['program'])->withCount([
            'pendaftarans' => function ($q) {
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
            }
        ])->get()->map(function ($course) use ($startDate) {
            $certs = Certificate::where('kursus_id', $course->id);
            if ($startDate) {
                $certs->where('created_at', '>=', $startDate);
            }
            $label = optional($course->program)->nama ? $course->program->nama . ' - ' . $course->judul : $course->judul;
            return [
                'kursus' => $label,
                'issued' => $certs->clone()->count(),
                'generated' => $certs->clone()->where('status', 'generated')->count(),
                'revoked' => $certs->clone()->where('status', 'revoked')->count(),
            ];
        })->filter(function ($item) {
            return $item['issued'] > 0;
        });

        // Expiry status
        $expiringSoon = Certificate::where('status', 'generated')
            ->where('expires_at', '!=', null)
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->with('peserta', 'kursus')
            ->count();

        $expired = Certificate::where('status', 'generated')
            ->where('expires_at', '!=', null)
            ->where('expires_at', '<=', now())
            ->with('peserta', 'kursus')
            ->count();

        // Trend (last 12 months or 30 days)
        $trend = $this->getTrend($period);

        // Recent activity
        $recent = Certificate::with('peserta', 'kursus')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.certificates.analytics', compact(
            'total', 'generated', 'revoked', 'queued',
            'byCourse', 'expiringSoon', 'expired',
            'trend', 'recent', 'period'
        ));
    }

    /**
     * Export analytics as CSV.
     */
    public function export(Request $request)
    {
        $period = $request->query('period', 'all');
        $startDate = $this->getStartDate($period);

        $query = Certificate::with('peserta', 'kursus');
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        $certificates = $query->get();

        $csv = "No. Sertifikat,Peserta,Program / Kursus,Status,Terbit,Valid Hingga,Hari Tersisa\n";

        foreach ($certificates as $cert) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $cert->no_sertifikat,
                $cert->peserta->nama ?? '-',
                (optional($cert->kursus->program)->nama ? $cert->kursus->program->nama . ' - ' : '') . ($cert->kursus->judul ?? $cert->kursus->nama ?? '-'),
                ucfirst($cert->status),
                $cert->issued_at?->format('Y-m-d') ?? '-',
                $cert->expires_at?->format('Y-m-d') ?? '-',
                $cert->daysUntilExpiry() ?? '-'
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="certificates-' . now()->format('Y-m-d') . '.csv"');
    }

    /**
     * Get start date based on period.
     */
    private function getStartDate($period)
    {
        return match ($period) {
            '30days' => now()->subDays(30),
            '90days' => now()->subDays(90),
            '1year' => now()->subYear(),
            default => null,
        };
    }

    /**
     * Get trend data (monthly for 1year, daily for 30days, etc).
     */
    private function getTrend($period)
    {
        $startDate = $this->getStartDate($period);
        $query = Certificate::query();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($period === '30days') {
            // Daily trend
            $trend = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $count = $query->clone()
                    ->whereDate('created_at', $date)
                    ->where('status', 'generated')
                    ->count();
                $trend[$date] = $count;
            }
        } else {
            // Monthly trend
            $trend = [];
            $months = $period === '1year' ? 12 : 3;
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $month = $date->format('Y-m');
                $count = $query->clone()
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'generated')
                    ->count();
                $trend[$month] = $count;
            }
        }

        return $trend;
    }
}
