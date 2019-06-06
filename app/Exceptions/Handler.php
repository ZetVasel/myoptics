<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        // Custom error 500 view on production
        if (app()->environment() == 'production') {
            if( $e instanceof ModelNotFoundException )
                return response()->view('errors.404', [], 404);
            elseif( $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException )
                return response()->view('errors.404', [], 404);
            // elseif( $e instanceof \Illuminate\Foundation\Http\Exceptions\MaintenanceModeException )
                // return response()->view('errors.503', [], 503);
            else
                // return parent::render($request, $e);
                return response()->view('errors.500', [], 500);
        }
        else
            return parent::render($request, $e);
    }
}
