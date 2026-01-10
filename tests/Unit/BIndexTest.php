<?php

namespace Privata\Tests;

use Privata\Tests\Stubs\User;

it('encrypts and decrypts given value', function () {
    User::create(['email' => 'test3@example.com']);
    User::create(['email' => 'test@example.com']);

    $existsFirst = User::whereEncrypted('email', 'test@example.com')->exists();
    $existsSecond = User::whereEncrypted('email', 'test4@example.com')->exists();

    expect($existsFirst)
        ->toBeTrue()
        ->and($existsSecond)
        ->toBeFalse();
});
