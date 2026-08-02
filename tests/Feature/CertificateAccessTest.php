<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Kursus;
use App\Models\Level;
use App\Models\Peserta;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_certificate_download_route_requires_authentication(): void
    {
        $response = $this->get(route('profile.certificate.download', ['id' => 1]));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_only_download_their_own_published_certificate(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $otherUser = User::factory()->create(['role' => 'peserta']);

        $participant = Peserta::create([
            'user_id' => $user->id,
            'nomor_peserta' => 'PS-2026-10001',
            'no_hp' => '-',
            'instansi' => 'Belum diisi',
        ]);

        $otherParticipant = Peserta::create([
            'user_id' => $otherUser->id,
            'nomor_peserta' => 'PS-2026-10002',
            'no_hp' => '-',
            'instansi' => 'Belum diisi',
        ]);

        $program = Program::create(['nama' => 'English']);
        $level = Level::create(['nama' => 'Dasar']);
        $course = Kursus::create([
            'program_id' => $program->id,
            'level_id' => $level->id,
            'nama' => 'English Dasar',
            'periode' => '2026-A',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'jam_pelajaran' => 72,
            'harga' => 100000,
            'harga_upi' => 90000,
            'kuota' => 20,
            'status' => 'buka',
        ]);

        $template = CertificateTemplate::create([
            'name' => 'Template Resmi',
            'institution_name' => 'UNIVERSITAS PENDIDIKAN INDONESIA',
            'unit_name' => 'BALAI BAHASA',
            'city' => 'Bandung',
            'header_logo_path' => 'images/certificate/logo_upi_ttd.png',
            'background_image_path' => 'images/certificate/border_backgorund.png',
            'signature_image_path' => 'images/certificate/ttd.png',
            'stamp_image_path' => 'images/certificate/label_ttd.png',
            'signer_name' => 'Prof. Ika Lestari Damayanti, M.A., Ph.D.',
            'signer_title' => 'Kepala Balai Bahasa',
            'signer_nip' => '197709192001122001',
            'certificate_prefix' => 'UN40.J7/TA.05.00',
            'is_active' => true,
        ]);

        $certificate = Certificate::create([
            'template_id' => $template->id,
            'certificate_name' => 'Sertifikat',
            'certificate_number' => '1/UN40.J7/TA.05.00/2026',
            'serial_number' => '001',
            'issued_date' => now()->toDateString(),
            'course_id' => $course->id,
            'participant_id' => $participant->id,
            'user_id' => $user->id,
            'status' => Certificate::STATUS_PUBLISHED,
            'participant_name_snapshot' => $user->name,
            'program_name_snapshot' => $program->nama,
            'course_name_snapshot' => $course->nama,
            'hours_snapshot' => 72,
            'start_date_snapshot' => $course->tanggal_mulai,
            'end_date_snapshot' => $course->tanggal_selesai,
            'signer_name_snapshot' => $template->signer_name,
            'signer_title_snapshot' => $template->signer_title,
            'signer_nip_snapshot' => $template->signer_nip,
            'city_snapshot' => $template->city,
        ]);

        $this->actingAs($otherUser)
            ->get(route('profile.certificate.download', $certificate->id))
            ->assertNotFound();

        $this->actingAs($user)
            ->get(route('profile.certificate.download', $certificate->id))
            ->assertOk();

        $draftCertificate = Certificate::create([
            'template_id' => $template->id,
            'certificate_name' => 'Draft Sertifikat',
            'certificate_number' => '2/UN40.J7/TA.05.00/2026',
            'serial_number' => '002',
            'issued_date' => now()->toDateString(),
            'course_id' => $course->id,
            'participant_id' => $otherParticipant->id,
            'user_id' => $otherUser->id,
            'status' => Certificate::STATUS_DRAFT,
            'participant_name_snapshot' => $otherUser->name,
            'program_name_snapshot' => $program->nama,
            'course_name_snapshot' => $course->nama,
            'hours_snapshot' => 72,
            'start_date_snapshot' => $course->tanggal_mulai,
            'end_date_snapshot' => $course->tanggal_selesai,
            'signer_name_snapshot' => $template->signer_name,
            'signer_title_snapshot' => $template->signer_title,
            'signer_nip_snapshot' => $template->signer_nip,
            'city_snapshot' => $template->city,
        ]);

        $this->actingAs($otherUser)
            ->get(route('profile.certificate.download', $draftCertificate->id))
            ->assertNotFound();
    }

    /**
     * Akun tanpa baris peserta tidak boleh menembus halaman sertifikat.
     * Sebelumnya id peserta yang null diteruskan apa adanya ke kueri, dan
     * penjagaannya cuma bersandar pada participant_id yang kebetulan NOT NULL.
     */
    public function test_user_without_participant_row_gets_not_found(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $this->actingAs($user)
            ->get(route('profile.certificate.download', 1))
            ->assertNotFound();

        $this->actingAs($user)
            ->get(route('profile.certificate.detail', 1))
            ->assertNotFound();

        $this->actingAs($user)
            ->get(route('profile.certificates'))
            ->assertOk();
    }
}
