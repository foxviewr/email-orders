<?php

namespace Tests\Unit\Auth;

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

class EmailVerificationNotificationControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_store_sends_verification_notification()
    {
        $request = Mockery::mock(Request::class);
        $user = Mockery::mock();
        $user->shouldReceive('hasVerifiedEmail')->andReturn(false);
        $user->shouldReceive('sendEmailVerificationNotification')->once();
        $request->shouldReceive('user')->andReturn($user);
        $controller = new EmailVerificationNotificationController();
        $response = $controller->store($request);
        $this->assertEquals(200, $response->status());
    }

    public function test_store_redirects_if_already_verified()
    {
        $request = Mockery::mock(Request::class);
        $user = Mockery::mock();
        $user->shouldReceive('hasVerifiedEmail')->andReturn(true);
        $request->shouldReceive('user')->andReturn($user);
        $controller = new EmailVerificationNotificationController();
        $response = $controller->store($request);
        $this->assertTrue($response->isRedirect());
    }
} 