<?php

namespace Tests\Unit\Auth;

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Mockery;
use PHPUnit\Framework\TestCase;

class VerifyEmailControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_invoke_verifies_email_success()
    {
        $request = Mockery::mock(EmailVerificationRequest::class);
        $user = Mockery::mock();
        $user->shouldReceive('hasVerifiedEmail')->andReturn(false);
        $user->shouldReceive('markEmailAsVerified')->andReturn(true);
        $request->shouldReceive('user')->andReturn($user);
        $controller = new VerifyEmailController();
        $response = $controller->__invoke($request);
        $this->assertStringContainsString('/dashboard?verified=1', $response->getTargetUrl());
    }

    public function test_invoke_redirects_if_already_verified()
    {
        $request = Mockery::mock(EmailVerificationRequest::class);
        $user = Mockery::mock();
        $user->shouldReceive('hasVerifiedEmail')->andReturn(true);
        $request->shouldReceive('user')->andReturn($user);
        $controller = new VerifyEmailController();
        $response = $controller->__invoke($request);
        $this->assertStringContainsString('/dashboard?verified=1', $response->getTargetUrl());
    }
} 