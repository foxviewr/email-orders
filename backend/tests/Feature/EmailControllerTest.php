<?php

namespace Tests\Feature;

use App\Models\Email;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EmailControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Set config for mailgun middleware
        config(['mail.incoming.middleware' => 'mailgun']);
        config(['services.mailgun.webhook_secret' => 'test_secret']);
    }

    private function mailgunSignaturePayload(array $data): array
    {
        $timestamp = time();
        $token = Str::random(16);
        $signature = hash_hmac('sha256', $timestamp . $token, config('services.mailgun.webhook_secret'));
        return array_merge($data, [
            'timestamp' => $timestamp,
            'token' => $token,
            'signature' => $signature,
        ]);
    }

    public function test_store_email_with_valid_mailgun_signature()
    {
        $order = Order::factory()->create();
        $payload = $this->mailgunSignaturePayload([
            'sender' => 'sender@example.com',
            'from' => 'Sender <sender@example.com>',
            'inReplyTo' => null,
            'subject' => 'Test Subject',
            'body' => 'Test body',
        ]);

        $response = $this->postJson('/api/email/store', $payload);
        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'uuid',
                'sender',
                'recipient',
                'subject',
                'body',
                'order_uuid',
            ]
        ]);
    }

    public function test_store_email_with_invalid_signature_fails()
    {
        $payload = [
            'sender' => 'sender@example.com',
            'from' => 'Sender <sender@example.com>',
            'inReplyTo' => null,
            'subject' => 'Test Subject',
            'body' => 'Test body',
            'timestamp' => time(),
            'token' => Str::random(16),
            'signature' => 'invalid',
        ];
        $response = $this->postJson('/api/email/store', $payload);
        $response->assertForbidden();
    }

    public function test_authenticated_user_can_send_reply()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        $email = Email::factory()->for($order)->create();

        $payload = [
            'sender' => 'sender@example.com',
            'senderName' => 'Sender',
            'recipient' => 'recipient@example.com',
            'recipientName' => 'Recipient',
            'subject' => 'Re: Test',
            'body' => 'Reply body',
            'inReplyTo' => $email->messageId,
        ];

        $response = $this->actingAs($user)->postJson('/api/email/send-reply', $payload);
        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'uuid',
                'sender',
                'recipient',
                'subject',
                'body',
                'order_uuid',
            ]
        ]);
    }

    public function test_guest_cannot_send_reply()
    {
        $order = Order::factory()->create();
        $email = Email::factory()->for($order)->create();
        $payload = [
            'sender' => 'sender@example.com',
            'senderName' => 'Sender',
            'recipient' => 'recipient@example.com',
            'recipientName' => 'Recipient',
            'subject' => 'Re: Test',
            'body' => 'Reply body',
            'inReplyTo' => $email->messageId,
        ];
        $response = $this->postJson('/api/email/send-reply', $payload);
        $response->assertUnauthorized();
    }
} 