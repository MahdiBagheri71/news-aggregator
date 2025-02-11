<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Private\AuthMeController;
use App\Http\Middleware\ForceJsonResponseMiddleware;

Route::prefix('/v1/private')
    ->middleware([
        ForceJsonResponseMiddleware::class,
        'throttle',
        'auth:sanctum',
    ])
    ->as('v1.private.')
    ->group(function () {

        Route::get('/me', [AuthMeController::class, 'show'])->name('auth-me.show');
    });
