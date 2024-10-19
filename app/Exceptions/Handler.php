<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Session\TokenMismatchException;
use RealRashid\SweetAlert\Facades\Alert;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
    if ($exception instanceof TokenMismatchException) {
    // Jika terjadi TokenMismatchException, alihkan ke halaman /home
    Alert::warning('Sesi Kedaluwarsa', 'Anda akan dialihkan ke halaman utama.');
    return redirect('login');
    }
    return parent::render($request, $exception);
    }
}
