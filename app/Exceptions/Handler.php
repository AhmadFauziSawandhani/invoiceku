<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Mockery\Exception\InvalidOrderException as ExceptionInvalidOrderException;

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
        if ($exception instanceof \Illuminate\Validation\ValidationException) {


                $msg = array();
                foreach ($exception->errors() as $row) {
                    $msg[] = $row[0];
                }

                return response()->json([
                    'code' => $exception->status,
                    'success' => false,
                    'messages' => implode(' ', $msg),
                    'data' => NULL,
                ], $exception->status);
            
        } elseif ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Entry for ' . str_replace('App\\', '', $exception->getModel()) . ' not found.',
                'data' => [],
            ], 404);
        } elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'This action is unauthorized.',
                'data' => [],
            ], 403);
            // } else if ($exception instanceof \Illuminate\Routing\Exceptions\InvalidSignatureException) {
            //     switch ($request->route()->getName()) {
            //         case 'verification.verify':
            //             $user = \App\User::where('uuid', $request->id)->firstOrFail();
            //             return redirect(url('auth/login/expired?email=' . $user->email));
            //         // ............
            //     }
        }
        return parent::render($request, $exception);
    }
}
