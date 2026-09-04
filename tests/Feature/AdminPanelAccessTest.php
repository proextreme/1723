<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminPanelAccessTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_a_content_administrator_can_open_the_panel(): void
    {
        $this->actingAs(User::factory()->contentAdministrator()->create())
            ->get('/admin')
            ->assertOk();
    }

    public function test_an_administrator_can_open_the_panel(): void
    {
        $this->actingAs(User::factory()->administrator()->create())
            ->get('/admin')
            ->assertOk();
    }

    public function test_a_soft_deleted_user_cannot_authenticate(): void
    {
        $user = User::factory()->create(['email' => 'gone@1723.test']);
        $user->delete();

        $this->assertFalse(Auth::attempt(['email' => 'gone@1723.test', 'password' => 'password']));
    }
}
