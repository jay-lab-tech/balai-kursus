<?php

namespace App\Jobs;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCertificateEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $certificate;

    /**
     * Create a new job instance.
     */
    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Execute the job.
     * Send certificate to peserta via email.
     */
    public function handle()
    {
        $cert = $this->certificate->fresh();
        
        // Get peserta and user
        $peserta = $cert->peserta;
        $user = $peserta->user;

        if (!$user || !$user->email) {
            \Log::warning("Certificate {$cert->no_sertifikat}: No email found for peserta {$peserta->id}");
            return;
        }

        // Build email content
        $downloadUrl = route('certificate.download', ['id' => $cert->id]);
        $verifyUrl = route('certificate.verify', ['code' => $cert->verification_code]);
        $courseName = $cert->kursus->judul ?? 'Kursus';

        // Send simple email (you can enhance this with a Mailable class later)
        try {
            Mail::send('emails.certificate-issued', [
                'peserta_name' => $peserta->nama,
                'course_name' => $courseName,
                'certificate_no' => $cert->no_sertifikat,
                'download_url' => $downloadUrl,
                'verify_url' => $verifyUrl,
                'issued_date' => $cert->issued_at->format('d M Y'),
            ], function ($message) use ($user, $courseName) {
                $message->to($user->email)
                    ->subject("Sertifikat Kursus: {$courseName}");
            });

            \Log::info("Certificate {$cert->no_sertifikat} email sent to {$user->email}");
        } catch (\Exception $e) {
            \Log::error("Failed to send certificate email: {$e->getMessage()}");
        }
    }
}
