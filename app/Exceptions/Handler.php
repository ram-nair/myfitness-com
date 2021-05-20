<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // if ($exception instanceof AuthenticationException) {
        //     return response()->json([
        //         "statusCode" => 403,
        //         "message" => "error",
        //         "errorMessage" => "Unauthenticated",
        //     ]);
        // }
        if ($request->expectsJson()) {
            return response()->json([
                "statusCode" => 403,
                "message" => "error",
                "errorMessage" => "Unauthenticated",
            ]);
            // return response()->json(['error' => 'Unauthenticated.'], 403);
        }
        if ($request->is('admin') || $request->is('admin/*')) {
            return redirect()->guest('/admin/login');
        }
        if ($request->is('vendor') || $request->is('vendor/*')) {
            return redirect()->guest('/vendor/login');
        }
        if ($request->is('store') || $request->is('store/*')) {
            return redirect()->guest('/store/login');
        }
        return redirect()->guest(route('login'));
    }
}
