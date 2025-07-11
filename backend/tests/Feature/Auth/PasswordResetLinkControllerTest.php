<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetLinkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_password_reset_link()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/forgot-password', [
            'email' => $user->email,
        ]);
        $response->assertOk();
        $response->assertJson(['status' => trans(Password::RESET_LINK_SENT)]);
    }

    public function test_user_cannot_request_password_reset_link_with_invalid_email()
    {
        $response = $this->postJson('/forgot-password', [
            'email' => 'not-an-email',
        ]);
        $response->assertStatus(422);
    }
} 