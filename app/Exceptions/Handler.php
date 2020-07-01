<?php

namespace App\Exceptions;

use App\ViewModel\ExceptionViewModel;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        if($request->segment(1) === 'api') {

            if($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'status'    => 404,
                    'error'     => ['NotFoundHttpException' => 404]
                ], 404);
            }

            $error = null;
            if(Config::get('app.debug') === true) {
                $error = ExceptionViewModel::default($exception);
            }
            else {
                $error = [
                    'message' => $exception->getMessage()
                ];
            }

            $status = null;
            if($exception instanceof AccessDeniedHttpException) {
                $status = 403;
            }
            else {
                $status = 500;
            }

            return response()->json([
                'status'    => $status,
                'error'     => $error,
            ], $status);
        }

        return parent::render($request, $exception);
    }
}
