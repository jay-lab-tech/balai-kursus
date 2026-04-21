<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesertaProgramRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_program_registration_stores_email_snapshot(): void
    {
        $user = User::factory()->create([
            'role' => 'peserta',
            'email' => 'peserta.gmail@example.com',
        ]);

        $peserta = Peserta::create([
            'user_id' => $user->id,
            'nomor_peserta' => 'PS-2026-30001',
            'no_hp' => '081234567890',
            'instansi' => 'UPI',
        ]);

        $program = Program::create([
            'nama' => 'English Conversation',
        ]);

        $this->actingAs($user)
            ->post(route('peserta.program.daftar', $program))
            ->assertRedirect(route('peserta.pendaftaran.index'));

        $this->assertDatabaseHas('pendaftarans', [
            'peserta_id' => $peserta->id,
            'program_id' => $program->id,
            'participant_email_snapshot' => 'peserta.gmail@example.com',
            'status_pendaftaran' => Pendaftaran::STATUS_MENUNGGU_TES,
        ]);

        $this->actingAs($user)
            ->get(route('peserta.pendaftaran.index'))
            ->assertOk()
            ->assertSee('peserta.gmail@example.com');
    }
}
