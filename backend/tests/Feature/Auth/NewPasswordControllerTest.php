<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class NewPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password_with_valid_token()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->postJson('/reset-password', [
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            'token' => $token,
        ]);

        $response->assertOk();
        $response->assertJson(['status' => trans(Password::PASSWORD_RESET)]);
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/reset-password', [
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            'token' => 'invalid-token',
        ]);
        $response->assertStatus(422);
    }
} 