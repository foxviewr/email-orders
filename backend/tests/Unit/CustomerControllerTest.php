<?php

namespace Tests\Unit;

use App\Http\Controllers\CustomerController;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class CustomerControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_returns_collection()
    {
        // Mock static methods on Customer
        $customerMock = Mockery::mock('overload:' . Customer::class);
        $customerMock->shouldReceive('with')->with(['orders'])->andReturnSelf();
        $customerMock->shouldReceive('withCount')->with(['orders'])->andReturnSelf();
        $customerMock->shouldReceive('get')->andReturn(['customer1', 'customer2']);

        // Mock static method on CustomerResource
        $resourceMock = Mockery::mock('alias:' . CustomerResource::class);
        $resourceMock->shouldReceive('collection')->with(['customer1', 'customer2'])->andReturn('resource-collection');

        $controller = new CustomerController();
        $result = $controller->getAll();
        $this->assertEquals('resource-collection', $result);
    }
} 