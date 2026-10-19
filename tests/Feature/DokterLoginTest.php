<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DokterLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_redirects_guest_to_login(): void
    {
        $this->withoutVite();

        $this->get('/')
            ->assertRedirect(route('login'));

        $this->get(route('login'))->assertOk();
    }

    public function test_dokter_can_log_in_and_view_the_dashboard(): void
    {
        $this->withoutVite();

        User::create([
            'nama' => 'Dokter',
            'role' => 'dokter',
            'email' => 'dokter@example.com',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email' => 'dokter@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dokter.dashboard'));

        $this->get(route('dokter.dashboard'))
            ->assertOk()
            ->assertSee('Halo Selamat Datang Dokter')
            ->assertDontSee('Halo Selamat Datang Pasien');
    }

    public function test_pasien_can_log_in_and_view_the_dashboard(): void
    {
        $this->withoutVite();

        User::create([
            'nama' => 'Pasien',
            'role' => 'pasien',
            'email' => 'pasien@example.com',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email' => 'pasien@example.com',
            'password' => 'password',
        ])->assertRedirect(route('pasien.dashboard'));

        $this->get(route('pasien.dashboard'))
            ->assertOk()
            ->assertSee('Halo Selamat Datang Pasien')
            ->assertDontSee('Halo Selamat Datang Dokter');
    }
}
