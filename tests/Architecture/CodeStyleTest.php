<?php

declare(strict_types=1);

test('application uses strict typing', function () {
    expect('App')->toUseStrictTypes();
});

test('classes have proper namespaces', function () {
    expect('App\Http\Controllers')
        ->toBeClasses()
        ->toOnlyBeUsedIn(['App', 'Database']);

    expect('App\Models')
        ->toBeClasses()
        ->toOnlyBeUsedIn(['App', 'Database']);

    expect('App\Services')
        ->toBeClasses()
        ->toOnlyBeUsedIn(['App', 'Database']);
});

test('traits are properly structured', function () {
    expect('App\Traits')
        ->toBeTraits()
        ->toOnlyBeUsedIn('App');
});

test('interfaces are properly structured', function () {
    expect('App\Interfaces')
        ->toBeInterfaces()
        ->toOnlyBeUsedIn('App');
});

test('enums are properly structured', function () {
    expect('App\Enums')
        ->toBeEnums()
        ->toOnlyBeUsedIn(['App', 'Database']);
});

test('controllers naming convention', function () {
    expect('App\Http\Controllers')
        ->toHaveSuffix('Controller');
});

test('traits naming convention', function () {
    expect('App\Traits')
        ->toHaveSuffix('Trait');
});

test('interfaces naming convention', function () {
    expect('App\Interfaces')
        ->toHaveSuffix('Interface');
});

test('enums naming convention', function () {
    expect('App\Enums')
        ->toHaveSuffix('Enum');
});
