<?php

declare(strict_types=1);

test('debug statements are removed', function () {
    // Check for debug functions
    expect(['dd', 'dump', 'var_dump', 'print_r'])
        ->not->toBeUsed();
});

test('debug comments are removed', function () {
    expect('App')
        ->toBeDirectory()
        ->not->toUse(['@todo', 'TODO:', 'FIXME:']);
});

test('no dump server responses in production', function () {
    expect('App\Http\Controllers')
        ->toBeClasses()
        ->not->toUse(['dd', 'dump']);
});
