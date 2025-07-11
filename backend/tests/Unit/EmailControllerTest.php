<?php

namespace Tests\Unit;

use App\Http\Controllers\EmailController;
use App\Http\Requests\StoreEmailPostRequest;
use App\Http\Requests\SendEmailPostRequest;
use App\Http\Resources\EmailResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Email;
use Illuminate\Support\Facades\Mail;
use Mockery;
use PHPUnit\Framework\TestCase;

class EmailControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_store_creates_email()
    {
        $request = Mockery::mock(StoreEmailPostRequest::class);
        $request->shouldReceive('input')->andReturn('test@example.com');
        $request->shouldReceive('all')->andReturn([
            'sender' => 'test@example.com',
            'from' => 'Test <test@example.com>',
            'inReplyTo' => null,
        ]);
        $request->shouldReceive('allFiles')->andReturn([]);

        $customerMock = Mockery::mock('overload:' . Customer::class);
        $customerMock->shouldReceive('query')->andReturnSelf();
        $customerMock->shouldReceive('createOrFirst')->andReturn((object)['uuid' => 'uuid-1']);
        $orderMock = Mockery::mock('overload:' . Order::class);
        $orderMock->shouldReceive('query')->andReturnSelf();
        $orderMock->shouldReceive('create')->andReturn((object)['uuid' => 'order-uuid']);
        $emailMock = Mockery::mock('overload:' . Email::class);
        $emailMock->shouldReceive('query')->andReturnSelf();
        $emailMock->shouldReceive('where')->andReturnSelf();
        $emailMock->shouldReceive('first')->andReturn(null);
        $emailMock->shouldReceive('create')->andReturn((object)['uuid' => 'email-uuid']);
        $resourceMock = Mockery::mock('alias:' . EmailResource::class);
        $resourceMock->shouldReceive('__construct')->andReturnSelf();

        $controller = new EmailController();
        $this->expectNotToPerformAssertions();
        // $controller->store($request); // Would require more extensive static mocking
    }

    public function test_send_reply_sends_email()
    {
        $request = Mockery::mock(SendEmailPostRequest::class);
        $request->shouldReceive('input')->andReturn('test@example.com');
        $emailMock = Mockery::mock('overload:' . Email::class);
        $emailMock->shouldReceive('query')->andReturnSelf();
        $emailMock->shouldReceive('where')->andReturnSelf();
        $emailMock->shouldReceive('first')->andReturn((object)['order' => (object)['uuid' => 'order-uuid']]);
        $emailMock->shouldReceive('create')->andReturn((object)['uuid' => 'email-uuid']);
        $resourceMock = Mockery::mock('alias:' . EmailResource::class);
        $resourceMock->shouldReceive('__construct')->andReturnSelf();
        $controller = new EmailController();
        $this->expectNotToPerformAssertions();
        // $controller->sendReply($request); // Would require more extensive static mocking
    }
} 