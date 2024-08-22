<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateMailgunMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // only if middleware configuration is mailgun
        if (!(config('mail.incoming.middleware') === 'mailgun')) {
            return $next($request);
        }

        // only if request is a POST
        if (!$request->isMethod('post')) {
            abort(Response::HTTP_METHOD_NOT_ALLOWED, 'Only POST requests are allowed.');
        }

        // verify the request
        if ($this->verify($request)) {
            return $next($request);
        }

        // request invalid, abort with 403
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
//        if (abs(time() - $request->input('timestamp')) > 60) {
//            return false;
//        }

        return $this->buildSignature($request) === $request->input('signature');
    }
}
