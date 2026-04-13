<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        // Handle authentication errors - redirect to login for web, JSON for API
        if ($e instanceof AuthenticationException) {
            // For API routes, return JSON error
            if ($request->is('api/*') || $request->expectsJson()) {
                Log::warning('API Authentication Failed', [
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now()->toDateTimeString()
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated. Please provide a valid token.',
                ], 401);
            }

            // For web routes, redirect to login page
            Log::warning('Web Authentication Failed - Redirecting to Login', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return redirect()->route('login');
        }

        // Handle API errors without session access
        if ($request->is('api/*') || $request->expectsJson()) {
            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
            $errorId = (string) Str::uuid();

            Log::error('API Exception', [
                'error_id'  => $errorId,
                'status'    => $status,
                'url'       => $request->fullUrl(),
                'method'    => $request->method(),
                'user_id'   => optional($request->user())->id,
                'ip'        => $request->ip(),
                'message'   => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            // For other API errors, return JSON
            return parent::render($request, $e);
        }

        $status  = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
        $errorId = (string) Str::uuid();

        Log::error('Unhandled exception', [
            'error_id'  => $errorId,
            'status'    => $status,
            'url'       => $request->fullUrl(),
            'user_id'   => optional($request->user())->id,
            'ip'        => $request->ip(),
            'exception' => $e,
        ]);

        // Store error in session only if session is available (web routes)
        if ($request->hasSession()) {
            try {
                $request->session()->flash('error_id', $errorId);
            } catch (\Exception $sessionEx) {
                // Session not available, skip silently
                Log::debug('Session not available for error storage', ['error_id' => $errorId]);
            }
        }

        // who gets details? debug or IT IPs/roles (customize)
        $itIps = []; // e.g. ['203.0.113.10']
        $showDetails = config('app.debug') || in_array($request->ip(), $itIps);

        $payload = [
            'status'      => $status,
            'errorId'     => $errorId,
            'showDetails' => $showDetails,
        ];
        if ($showDetails) {
            $payload['message'] = $e->getMessage();
            $payload['trace']   = $e->getTraceAsString();
        }

        try {
            $view = view()->exists("errors.$status") ? "errors.$status" : 'errors.generic';
            return response()->view($view, $payload, $status);
        } catch (Throwable $renderEx) {
            Log::critical('Failed rendering error view', [
                'error_id' => $errorId,
                'status'   => $status,
                'render_exception' => $renderEx->getMessage(),
            ]);

            // minimal fallback so users still see something
            return response(
                '<!doctype html><meta charset="utf-8"><title>Error</title>
                 <div style="font-family:system-ui;padding:24px">
                   <h1>Whoops! There was an error.</h1>
                   <p>Please contact IT and share this code: <b>' . $errorId . '</b></p>
                   <a href="/" style="display:inline-block;margin-top:10px">Go to Home</a>
                 </div>',
                $status,
                ['Content-Type' => 'text/html; charset=utf-8']
            );
        }
    }
}
