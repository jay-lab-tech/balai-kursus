<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstrukturManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_instruktur(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.instruktur.store'), [
                'name' => 'Instruktur Baru',
                'email' => 'baru.instruktur@balai.test',
                'password' => 'password',
                'nama_instr' => 'Instruktur Baru',
                'spesialisasi' => 'Speaking',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.instruktur.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'baru.instruktur@balai.test',
            'role' => 'instruktur',
        ]);

        $userId = User::where('email', 'baru.instruktur@balai.test')->value('id');

        $this->assertDatabaseHas('instrukturs', [
            'user_id' => $userId,
            'nama_instr' => 'Instruktur Baru',
            'spesialisasi' => 'Speaking',
        ]);
    }
}
