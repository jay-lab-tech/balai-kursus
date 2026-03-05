<?php

namespace App\Jobs;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class GenerateCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $certificate;

    /**
     * Create a new job instance.
     *
     * @param Certificate $certificate
     */
    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Execute the job.
     *
     * This job uses wkhtmltopdf to render an HTML template into a PDF.
     */
    public function handle()
    {
        $cert = $this->certificate->fresh();

        // Get template for this course, fallback to default
        $template = \App\Models\CertificateTemplate::forCourse($cert->kursus_id);

        // Prepare data for view
        $viewData = [
            'certificate' => $cert,
            'peserta' => $cert->peserta,
            'kursus' => $cert->kursus,
            'template' => $template,
        ];

        // Use course-specific template if available, otherwise default certificate template view
        if ($template && $template->html_template) {
            $html = $this->renderCustomTemplate($template->html_template, $viewData);
        } else {
            $html = view('certificates.template', $viewData)->render();
        }
        // write to temporary file
        $dir = storage_path('app/certificates');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $tmpHtml = $dir . '/tmp_' . $cert->id . '.html';
        file_put_contents($tmpHtml, $html);

        $outputPdf = $dir . '/cert_' . $cert->id . '.pdf';

        // call wkhtmltopdf
        // try wkhtmltopdf first
        $usedPdf = false;
        $path = 'certificates/' . now()->year . '/cert_' . $cert->id . '.pdf';

        $process = new Process([
            'wkhtmltopdf',
            '--enable-local-file-access',
            $tmpHtml,
            $outputPdf,
        ]);
        $process->setTimeout(120);
        $process->run();

        if ($process->isSuccessful() && file_exists($outputPdf)) {
            Storage::disk('local')->put($path, file_get_contents($outputPdf));
            $usedPdf = true;
        }

        if (!$usedPdf) {
            // fallback to dompdf
            try {
                \PDF::loadHtml($html)
                    ->setPaper('a4', 'landscape')
                    ->save($outputPdf);
                Storage::disk('local')->put($path, file_get_contents($outputPdf));
                $usedPdf = true;
            } catch (\Throwable $e) {
                throw new \Exception('PDF generation failed: ' . $e->getMessage());
            }
        }

        // update record
        $cert->file_path = $path;
        $cert->generated_at = now();
        $cert->status = 'generated';
        $cert->save();

        // clean up
        @unlink($tmpHtml);
        @unlink($outputPdf);
    }

    /**
     * Render custom template HTML with variable substitution.
     */
    private function renderCustomTemplate($templateHtml, $data)
    {
        $cert = $data['certificate'];
        $peserta = $data['peserta'];
        $kursus = $data['kursus'];

        // Replace placeholders
        $html = $templateHtml;
        $html = str_replace('{{NAMA}}', $peserta->nama ?? '-', $html);
        $html = str_replace('{{KURSUS}}', $kursus->judul ?? '-', $html);
        $html = str_replace('{{TANGGAL}}', $cert->issued_at?->format('d M Y') ?? now()->format('d M Y'), $html);
        $html = str_replace('{{NO_SERTIF}}', $cert->no_sertifikat, $html);

        // Include signature if available
        if ($cert->template?->signature_path && Storage::disk('local')->exists($cert->template->signature_path)) {
            $sigPath = storage_path('app/' . $cert->template->signature_path);
            $sigBase64 = base64_encode(file_get_contents($sigPath));
            $html = str_replace('{{SIGNATURE}}', '<img src="data:image/png;base64,' . $sigBase64 . '" style="max-height:60px;" />', $html);
        }

        return $html;
    }
}
