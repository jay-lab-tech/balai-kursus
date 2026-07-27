<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Instruktur;
use App\Models\Kursus;
use App\Models\Level;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Program;
use App\Models\Risalah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateAdminWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_batch_draft_certificates_without_overwriting_published_ones(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $template = CertificateTemplate::active()->firstOrFail();

        [$participantOne, $userOne] = $this->createParticipant('PS-2026-20001');
        [$participantTwo, $userTwo] = $this->createParticipant('PS-2026-20002');

        $this->registerParticipant($participantOne, $course);
        $this->registerParticipant($participantTwo, $course);

        Certificate::create([
            'template_id' => $template->id,
            'certificate_name' => 'Sertifikat Lama',
            'certificate_number' => '1/UN40.J7/TA.05.00/2026',
            'serial_number' => '001',
            'issued_date' => now()->toDateString(),
            'course_id' => $course->id,
            'participant_id' => $participantOne->id,
            'user_id' => $userOne->id,
            'status' => Certificate::STATUS_PUBLISHED,
            'participant_name_snapshot' => $userOne->name,
            'program_name_snapshot' => $course->program->nama,
            'course_name_snapshot' => $course->nama,
            'hours_snapshot' => $course->jam_pelajaran,
            'start_date_snapshot' => $course->tanggal_mulai,
            'end_date_snapshot' => $course->tanggal_selesai,
            'signer_name_snapshot' => $template->signer_name,
            'signer_title_snapshot' => $template->signer_title,
            'signer_nip_snapshot' => $template->signer_nip,
            'city_snapshot' => $template->city,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.certificates.batch.store'), [
                'course_id' => $course->id,
                'issued_date' => now()->toDateString(),
                'certificate_name' => 'Sertifikat Batch IELTS',
            ])
            ->assertRedirect(route('admin.certificates.index'));

        $this->assertDatabaseHas('certificates', [
            'course_id' => $course->id,
            'participant_id' => $participantOne->id,
            'status' => Certificate::STATUS_PUBLISHED,
            'certificate_name' => 'Sertifikat Lama',
        ]);

        $this->assertDatabaseHas('certificates', [
            'course_id' => $course->id,
            'participant_id' => $participantTwo->id,
            'status' => Certificate::STATUS_DRAFT,
            'certificate_name' => 'Sertifikat Batch IELTS',
        ]);
    }

    public function test_admin_can_preview_certificate_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $template = CertificateTemplate::active()->firstOrFail();
        [$participant, $user] = $this->createParticipant('PS-2026-20003');

        $certificate = Certificate::create([
            'template_id' => $template->id,
            'certificate_name' => 'Sertifikat Preview',
            'certificate_number' => '2/UN40.J7/TA.05.00/2026',
            'serial_number' => '002',
            'issued_date' => now()->toDateString(),
            'course_id' => $course->id,
            'participant_id' => $participant->id,
            'user_id' => $user->id,
            'status' => Certificate::STATUS_DRAFT,
            'participant_name_snapshot' => $user->name,
            'program_name_snapshot' => $course->program->nama,
            'course_name_snapshot' => $course->nama,
            'hours_snapshot' => $course->jam_pelajaran,
            'start_date_snapshot' => $course->tanggal_mulai,
            'end_date_snapshot' => $course->tanggal_selesai,
            'signer_name_snapshot' => $template->signer_name,
            'signer_title_snapshot' => $template->signer_title,
            'signer_nip_snapshot' => $template->signer_nip,
            'city_snapshot' => $template->city,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.certificates.preview', $certificate->id))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_publish_all_draft_certificates_for_a_course(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $template = CertificateTemplate::active()->firstOrFail();

        [$participantOne, $userOne] = $this->createParticipant('PS-2026-20004');
        [$participantTwo, $userTwo] = $this->createParticipant('PS-2026-20005');

        foreach ([[$participantOne, $userOne, '003'], [$participantTwo, $userTwo, '004']] as [$participant, $user, $serial]) {
            Certificate::create([
                'template_id' => $template->id,
                'certificate_name' => 'Sertifikat Draft',
                'certificate_number' => "{$serial}/UN40.J7/TA.05.00/2026",
                'serial_number' => $serial,
                'issued_date' => now()->toDateString(),
                'course_id' => $course->id,
                'participant_id' => $participant->id,
                'user_id' => $user->id,
                'status' => Certificate::STATUS_DRAFT,
                'participant_name_snapshot' => $user->name,
                'program_name_snapshot' => $course->program->nama,
                'course_name_snapshot' => $course->nama,
                'hours_snapshot' => $course->jam_pelajaran,
                'start_date_snapshot' => $course->tanggal_mulai,
                'end_date_snapshot' => $course->tanggal_selesai,
                'signer_name_snapshot' => $template->signer_name,
                'signer_title_snapshot' => $template->signer_title,
                'signer_nip_snapshot' => $template->signer_nip,
                'city_snapshot' => $template->city,
            ]);
        }

        $this->actingAs($admin)
            ->post(route('admin.certificates.batch.publish'), [
                'course_id' => $course->id,
            ])
            ->assertRedirect(route('admin.certificates.index'));

        $this->assertDatabaseCount('certificates', 2);
        $this->assertSame(
            2,
            Certificate::query()->where('course_id', $course->id)->where('status', Certificate::STATUS_PUBLISHED)->count()
        );
    }

    public function test_admin_can_revoke_and_restore_certificate_to_draft(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $template = CertificateTemplate::active()->firstOrFail();
        [$participant, $user] = $this->createParticipant('PS-2026-20006');

        $certificate = Certificate::create([
            'template_id' => $template->id,
            'certificate_name' => 'Sertifikat Resmi',
            'certificate_number' => '005/UN40.J7/TA.05.00/2026',
            'serial_number' => '005',
            'issued_date' => now()->toDateString(),
            'course_id' => $course->id,
            'participant_id' => $participant->id,
            'user_id' => $user->id,
            'status' => Certificate::STATUS_PUBLISHED,
            'participant_name_snapshot' => $user->name,
            'program_name_snapshot' => $course->program->nama,
            'course_name_snapshot' => $course->nama,
            'hours_snapshot' => $course->jam_pelajaran,
            'start_date_snapshot' => $course->tanggal_mulai,
            'end_date_snapshot' => $course->tanggal_selesai,
            'signer_name_snapshot' => $template->signer_name,
            'signer_title_snapshot' => $template->signer_title,
            'signer_nip_snapshot' => $template->signer_nip,
            'city_snapshot' => $template->city,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.certificates.revoke', $certificate->id))
            ->assertRedirect(route('admin.certificates.index'));

        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id,
            'status' => Certificate::STATUS_REVOKED,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.certificates.restore-draft', $certificate->id))
            ->assertRedirect(route('admin.certificates.index'));

        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id,
            'status' => Certificate::STATUS_DRAFT,
        ]);
    }

    public function test_batch_generation_can_filter_by_payment_and_attendance_threshold(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();

        [$participantOne] = $this->createParticipant('PS-2026-20007');
        [$participantTwo] = $this->createParticipant('PS-2026-20008');

        $registrationOne = $this->registerParticipant($participantOne, $course, [
            'status_pembayaran' => Pendaftaran::PAYMENT_LUNAS,
        ]);
        $registrationTwo = $this->registerParticipant($participantTwo, $course, [
            'status_pembayaran' => Pendaftaran::PAYMENT_DP,
            'terbayar' => (int) ($course->harga * 0.3),
        ]);

        $this->recordAttendance($course, $registrationOne, ['H', 'H', 'H', 'A']);
        $this->recordAttendance($course, $registrationTwo, ['H', 'H', 'H', 'H']);

        $this->actingAs($admin)
            ->post(route('admin.certificates.batch.store'), [
                'course_id' => $course->id,
                'issued_date' => now()->toDateString(),
                'certificate_name' => 'Sertifikat Filtered',
                'registration_status' => 'selesai',
                'payment_status' => 'lunas',
                'min_attendance_percent' => 70,
            ])
            ->assertRedirect(route('admin.certificates.index'));

        $this->assertDatabaseHas('certificates', [
            'course_id' => $course->id,
            'participant_id' => $participantOne->id,
            'certificate_name' => 'Sertifikat Filtered',
            'status' => Certificate::STATUS_DRAFT,
        ]);

        $this->assertDatabaseMissing('certificates', [
            'course_id' => $course->id,
            'participant_id' => $participantTwo->id,
        ]);
    }

    protected function createCourse(): Kursus
    {
        $program = Program::create(['nama' => 'IELTS Preparation Course']);
        $level = Level::create(['nama' => 'Menengah']);

        return Kursus::create([
            'program_id' => $program->id,
            'level_id' => $level->id,
            'nama' => 'IELTS 72 JP',
            'periode' => '2026-A',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'jam_pelajaran' => 72,
            'harga' => 150000,
            'harga_upi' => 120000,
            'kuota' => 20,
            'status' => 'buka',
        ]);
    }

    protected function createParticipant(string $nomor): array
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $participant = Peserta::create([
            'user_id' => $user->id,
            'nomor_peserta' => $nomor,
            'no_hp' => '-',
            'instansi' => 'Belum diisi',
        ]);

        return [$participant, $user];
    }

    protected function registerParticipant(Peserta $participant, Kursus $course, array $overrides = []): Pendaftaran
    {
        return Pendaftaran::create(array_merge([
            'peserta_id' => $participant->id,
            'program_id' => $course->program_id,
            'level_id' => $course->level_id,
            'kursus_id' => $course->id,
            'status_pendaftaran' => Pendaftaran::STATUS_SELESAI,
            'status_pembayaran' => Pendaftaran::PAYMENT_LUNAS,
            'total_bayar' => $course->harga,
            'terbayar' => $course->harga,
        ], $overrides));
    }

    protected function recordAttendance(Kursus $course, Pendaftaran $registration, array $statuses): void
    {
        $instrukturUser = User::factory()->create(['role' => 'instruktur']);
        $instruktur = Instruktur::create([
            'user_id' => $instrukturUser->id,
            'nama_instr' => $instrukturUser->name,
            'spesialisasi' => 'General',
        ]);

        foreach ($statuses as $index => $status) {
            $risalah = Risalah::create([
                'kursus_id' => $course->id,
                'instruktur_id' => $instruktur->id,
                'pertemuan_ke' => $index + 1,
                'tgl_pertemuan' => now()->addDays($index)->toDateString(),
                'materi' => 'Materi '.($index + 1),
            ]);

            Absensi::create([
                'risalah_id' => $risalah->id,
                'pendaftaran_id' => $registration->id,
                'status' => $status,
            ]);
        }
    }
}
