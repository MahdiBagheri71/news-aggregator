<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Public\Auth\AuthLoginController;
use App\Http\Middleware\ForceJsonResponseMiddleware;

Route::prefix('/v1/public')
    ->middleware([
        ForceJsonResponseMiddleware::class,
        'throttle',
    ])
    ->as('v1.public.')
    ->group(function () {
        Route::get('/login', [AuthLoginController::class, 'show'])->name('auth-login.show');

    });
