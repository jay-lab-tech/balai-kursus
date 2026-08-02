<?php

namespace Tests\Feature;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    /**
     * Ketiga peran memakai satu view yang sama; yang berbeda hanya kerangkanya.
     * Dulu instruktur punya view sendiri dan admin sama sekali tidak punya
     * tautan ke halaman ini.
     */
    public function test_profile_page_is_displayed_for_every_role(): void
    {
        foreach (['admin', 'instruktur', 'peserta'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            if ($role === 'peserta') {
                Peserta::factory()->create(['user_id' => $user->id]);
            }

            $this->actingAs($user)->get('/profile')->assertOk();
        }
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_peserta_data_is_updated_alongside_the_account(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $peserta = Peserta::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Alya Putri',
                'email' => $user->email,
                'no_hp' => '081234567890',
                'instansi' => 'Universitas Pendidikan Indonesia',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $peserta->refresh();

        $this->assertSame('081234567890', $peserta->no_hp);
        $this->assertSame('Universitas Pendidikan Indonesia', $peserta->instansi);
    }

    /**
     * Validasi data peserta dulu dijalankan setelah User::save(), jadi nama dan
     * email sudah berubah walaupun pengguna melihat pesan galat.
     */
    public function test_account_is_untouched_when_peserta_data_is_invalid(): void
    {
        $user = User::factory()->create(['role' => 'peserta', 'name' => 'Nama Lama']);
        Peserta::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->from('/profile')
            ->patch('/profile', [
                'name' => 'Nama Baru',
                'email' => $user->email,
                'no_hp' => '',
            ])
            ->assertSessionHasErrors('no_hp');

        $this->assertSame('Nama Lama', $user->refresh()->name);
    }

    /**
     * Rutenya sudah ada sejak awal, tapi formulirnya tidak pernah dirender
     * sehingga tidak ada cara mengganti kata sandi dari dalam aplikasi.
     */
    public function test_password_can_be_updated_from_the_profile_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'kata-sandi-baru',
                'password_confirmation' => 'kata-sandi-baru',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('kata-sandi-baru', $user->refresh()->password));
    }

    public function test_password_is_not_updated_without_the_current_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'salah',
                'password' => 'kata-sandi-baru',
                'password_confirmation' => 'kata-sandi-baru',
            ])
            ->assertSessionHasErrorsIn('updatePassword', 'current_password');

        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    /**
     * Rute hapus akun bawaan Breeze dilepas: cascadeOnDelete pada pesertas dan
     * instrukturs membuat satu permintaan menghapus seluruh jejak akademik dan
     * keuangan orang tersebut.
     */
    public function test_account_deletion_route_is_not_registered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete('/profile', ['password' => 'password'])
            ->assertStatus(405);

        $this->assertNotNull($user->fresh());
    }
}
