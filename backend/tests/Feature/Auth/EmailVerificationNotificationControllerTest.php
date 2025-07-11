<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationNotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_request_verification_notification()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->postJson('/email/verification-notification');
        $response->assertOk();
        $response->assertJson(['status' => 'verification-link-sent']);
    }

    public function test_verified_user_redirects_on_verification_notification_request()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $response = $this->actingAs($user)->postJson('/email/verification-notification');
        $response->assertRedirect('/dashboard');
    }

    public function test_guest_cannot_request_verification_notification()
    {
        $response = $this->postJson('/email/verification-notification');
        $response->assertUnauthorized();
    }
} 