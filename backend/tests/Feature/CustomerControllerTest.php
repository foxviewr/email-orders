<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_all_customers()
    {
        $user = User::factory()->create();
        $customers = Customer::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/customers/get/all');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'name',
                    'email',
                    'orders',
                    'orders_count',
                ]
            ]
        ]);
    }

    public function test_guest_cannot_get_customers()
    {
        $response = $this->getJson('/api/customers/get/all');
        $response->assertUnauthorized();
    }
} 