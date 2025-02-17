<?php

declare(strict_types=1);

test('controllers have Controller suffix', function () {
    expect('App\Http\Controllers')
        ->toHaveProperSuffix('Controller');
});

test('controllers are in correct namespace', function () {
    expect('App\Http\Controllers')
        ->toBeClasses()
        ->toOnlyBeUsedIn('App\Http\Controllers');
});

test('controllers extend base controller', function () {
    expect('App\Http\Controllers')
        ->toExtend(\App\Http\Controllers\Controller::class);
});
