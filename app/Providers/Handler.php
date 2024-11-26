<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Handler extends ExceptionHandler
{
    /**
     * A lista de exceções que não são reportadas.
     */
    protected $dontReport = [];

    /**
     * A lista de tipos de entrada que nunca são exibidos nos logs.
     */
    protected $dontFlash = ['password', 'password_confirmation'];

    /**
     * Reportar ou registrar uma exceção.
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Renderizar uma exceção para uma resposta HTTP.
     */
    public function render($request, Throwable $e)
    {
        // Tratamento de erros de validação
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        }

        // Tratamento de outros erros HTTP
        if ($e instanceof HttpException) {
            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], $e->getStatusCode());
        }

        // Caso a exceção não seja do tipo HttpException
        return response()->json([
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage(),
        ], 500);
    }
}