<?php

namespace Tests\Unit;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

class OrderControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_returns_orders()
    {
        $orderMock = Mockery::mock('overload:' . Order::class);
        $orderMock->shouldReceive('with')->with(['customer', 'emails'])->andReturnSelf();
        $orderMock->shouldReceive('get')->andReturnSelf();
        $orderMock->shouldReceive('toArray')->andReturn([
            ['uuid' => '1', 'number' => 'A', 'customer' => ['name' => 'C', 'email' => 'E'], 'emails' => []],
        ]);
        $controller = new OrderController();
        $result = $controller->getAll();
        $this->assertIsArray($result);
        $this->assertEquals('1', $result[0]['uuid']);
    }

    public function test_get_by_customer_uuid_returns_orders()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->andReturn(['customerUuid' => 'uuid-1']);
        $customerMock = Mockery::mock('overload:' . Customer::class);
        $customerMock->shouldReceive('where')->with('uuid', 'uuid-1')->andReturnSelf();
        $customerMock->shouldReceive('first')->andReturn($customerMock);
        $orderMock = Mockery::mock('overload:' . Order::class);
        $orderMock->shouldReceive('with')->with(['customer', 'emails'])->andReturnSelf();
        $orderMock->shouldReceive('where')->with('customer_uuid', 'uuid-1')->andReturnSelf();
        $orderMock->shouldReceive('get')->andReturnSelf();
        $orderMock->shouldReceive('toArray')->andReturn([
            ['uuid' => '1', 'number' => 'A', 'customer' => ['name' => 'C', 'email' => 'E'], 'emails' => []],
        ]);
        $controller = new OrderController();
        $result = $controller->getByCustomerUuid($request);
        $this->assertIsArray($result);
    }

    public function test_get_by_customer_uuid_returns_404_for_invalid_customer()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->andReturn(['customerUuid' => 'uuid-1']);
        $customerMock = Mockery::mock('overload:' . Customer::class);
        $customerMock->shouldReceive('where')->with('uuid', 'uuid-1')->andReturnSelf();
        $customerMock->shouldReceive('first')->andReturn(null);
        $controller = new OrderController();
        $response = $controller->getByCustomerUuid($request);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_get_by_uuid_returns_order()
    {
        $orderMock = Mockery::mock('overload:' . Order::class);
        $orderMock->shouldReceive('with')->with(['customer', 'emails'])->andReturnSelf();
        $orderMock->shouldReceive('where')->with('uuid', 'uuid-1')->andReturnSelf();
        $orderMock->shouldReceive('first')->andReturn((object)[
            'uuid' => 'uuid-1',
            'number' => 'A',
            'customer' => (object)['name' => 'C', 'email' => 'E'],
            'emails' => [],
            'toArray' => fn() => [
                'uuid' => 'uuid-1',
                'number' => 'A',
                'customer' => ['name' => 'C', 'email' => 'E'],
                'emails' => [],
            ],
        ]);
        $controller = new OrderController();
        $result = $controller->getByUuid('uuid-1');
        $this->assertIsArray($result);
        $this->assertEquals('uuid-1', $result['uuid']);
    }

    public function test_get_by_uuid_returns_404_for_invalid_order()
    {
        $orderMock = Mockery::mock('overload:' . Order::class);
        $orderMock->shouldReceive('with')->with(['customer', 'emails'])->andReturnSelf();
        $orderMock->shouldReceive('where')->with('uuid', 'invalid-uuid')->andReturnSelf();
        $orderMock->shouldReceive('first')->andReturn(null);
        $controller = new OrderController();
        $response = $controller->getByUuid('invalid-uuid');
        $this->assertEquals(404, $response->getStatusCode());
    }
} 