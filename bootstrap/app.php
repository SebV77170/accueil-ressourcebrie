<?php

use Illuminate\Foundation\Application;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Authentification requise pour accéder à cette ressource.",
                ], 401);
            }

            return redirect()
                ->route('login')
                ->withErrors(['global' => "Vous devez vous connecter pour continuer."]);
        });

        $exceptions->render(function (AuthorizationException $exception, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Vous n'êtes pas autorisé à effectuer cette action.",
                ], 403);
            }

            return redirect()
                ->back()
                ->withErrors(['global' => "Vous n'êtes pas autorisé à effectuer cette action."])
                ->withInput();
        });

        $exceptions->render(function (ModelNotFoundException|NotFoundHttpException $exception, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Ressource introuvable.",
                ], 404);
            }

            return response()->view('errors.generic', [
                'title' => 'Ressource introuvable',
                'message' => "La page ou la ressource demandée n'existe pas.",
            ], 404);
        });

        $exceptions->render(function (\DomainException|\RuntimeException $exception, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], 400);
            }

            return redirect()
                ->back()
                ->withErrors(['global' => $exception->getMessage()])
                ->withInput();
        });

        $exceptions->render(function (\Throwable $exception, Request $request) {
            report($exception);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Une erreur inattendue est survenue. Merci de réessayer.",
                ], 500);
            }

            return response()->view('errors.generic', [
                'title' => 'Erreur inattendue',
                'message' => "Une erreur inattendue est survenue. Merci de réessayer ou de contacter l'équipe.",
            ], 500);
        });
    })->create();
