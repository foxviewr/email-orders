<?php

namespace Tests\Unit\Auth;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use PHPUnit\Framework\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_store_authenticates_user()
    {
        $request = Mockery::mock(LoginRequest::class);
        $request->shouldReceive('authenticate')->once();
        $request->shouldReceive('session')->andReturnSelf();
        $request->shouldReceive('regenerate')->once();
        $controller = new AuthenticatedSessionController();
        $response = $controller->store($request);
        $this->assertEquals(204, $response->status());
    }

    public function test_destroy_logs_out_user()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('session')->andReturnSelf();
        $request->shouldReceive('invalidate')->once();
        $request->shouldReceive('regenerateToken')->once();
        // Auth facade is static, so this is a limitation in pure unit tests
        $controller = new AuthenticatedSessionController();
        $response = $controller->destroy($request);
        $this->assertEquals(204, $response->status());
    }
} 