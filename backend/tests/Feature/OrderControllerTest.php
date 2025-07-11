<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_all_orders()
    {
        $user = User::factory()->create();
        $orders = Order::factory()->count(2)->create();

        $response = $this->actingAs($user)->getJson('/api/orders/get/all');

        $response->assertOk();
        $response->assertJsonIsArray();
        $this->assertCount(2, $response->json());
    }

    public function test_guest_cannot_get_orders()
    {
        $response = $this->getJson('/api/orders/get/all');
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_get_orders_by_customer_uuid()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $orders = Order::factory()->count(2)->for($customer)->create();

        $response = $this->actingAs($user)->postJson('/api/orders/get/customer/' . $customer->uuid, [
            'customerUuid' => $customer->uuid,
        ]);

        $response->assertOk();
        $response->assertJsonIsArray();
        $this->assertCount(2, $response->json());
    }

    public function test_get_orders_by_customer_uuid_returns_404_for_invalid_customer()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/orders/get/customer/invalid-uuid', [
            'customerUuid' => 'invalid-uuid',
        ]);
        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_get_order_by_uuid()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/orders/get/' . $order->uuid);

        $response->assertOk();
        $response->assertJsonStructure([
            'uuid',
            'number',
            'customer_name',
            'customer_email',
            'emails',
            'emails_count',
        ]);
    }

    public function test_get_order_by_uuid_returns_404_for_invalid_order()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/orders/get/invalid-uuid');
        $response->assertStatus(404);
    }
} 