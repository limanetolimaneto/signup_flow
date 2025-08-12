<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // public function render($request, Throwable $exception)
    // {
    //     if ($exception instanceof HttpException && $exception->getStatusCode() === 419) {
    //         return redirect('/confirm')
    //             ->with('errorKey', 'form_2')
    //             ->with('codeKey', 'confirm')
    //             ->with('errorMessage', ["Your verification session has timed out. Please start the registration process again."]);
    //     }

    //     return parent::render($request, $exception);
    // }
}
