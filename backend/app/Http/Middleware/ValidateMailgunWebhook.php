<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateMailgunWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isMethod('post')) {
            abort(Response::HTTP_METHOD_NOT_ALLOWED, 'Only POST requests are allowed.');
        }

        if ($this->verify($request)) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    protected function buildSignature($request): string
    {
        return hash_hmac(
            algo: 'sha256',
            data: $request->input('timestamp') . $request->input('token'),
            key: config('services.mailgun.webhook_secret'),
        );
    }

    protected function verify($request): bool
    {
        if (abs(time() - $request->input('timestamp')) > 60) {
            return false;
        }

        return $this->buildSignature($request) === $request->input('signature');
    }
}
