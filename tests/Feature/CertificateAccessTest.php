<?php

namespace Tests\Feature;

use App\Models\Certificate;
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
        $response = $this->get(route('certificate.download', ['id' => 1]));

        $response->assertRedirect(route('login.cas'));
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
            'harga' => 100000,
            'harga_upi' => 90000,
            'kuota' => 20,
            'status' => 'buka',
        ]);

        $certificate = Certificate::create([
            'certificate_name' => 'Sertifikat',
            'certificate_image_path' => 'certificates/sample.jpg',
            'course_id' => $course->id,
            'participant_id' => $participant->id,
            'user_id' => $user->id,
            'status' => 'published',
        ]);

        $this->actingAs($otherUser)
            ->get(route('certificate.download', $certificate->id))
            ->assertNotFound();

        $this->actingAs($user)
            ->get(route('certificate.download', $certificate->id))
            ->assertOk();

        $unpublishedCertificate = Certificate::create([
            'certificate_name' => 'Draft Sertifikat',
            'certificate_image_path' => 'certificates/sample-2.jpg',
            'course_id' => $course->id,
            'participant_id' => $otherParticipant->id,
            'user_id' => $otherUser->id,
            'status' => 'pending',
        ]);

        $this->actingAs($otherUser)
            ->get(route('certificate.download', $unpublishedCertificate->id))
            ->assertNotFound();
    }
}
