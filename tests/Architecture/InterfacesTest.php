<?php

declare(strict_types=1);

test('entities implement JsonSerializable', function () {
    expect('App\Models')
        ->toImplement(\JsonSerializable::class);
});
