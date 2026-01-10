<?php

namespace Privata\Tests;

use Privata\Tests\Stubs\User;

it('checks whether the encrypted AND query functions correctly', function () {
    User::create(['email' => 'test3@example.com']);
    User::create(['email' => 'test@example.com']);

    $existsFirst = User::whereEncrypted('email', 'test@example.com')->exists();
    $existsSecond = User::whereEncrypted('email', 'test4@example.com')->exists();

    expect($existsFirst)
        ->toBeTrue()
        ->and($existsSecond)
        ->toBeFalse();
});

it('checks whether the encrypted OR query functions correctly', function () {
    User::create(['email' => 'test3@example.com']);
    User::create(['email' => 'test@example.com']);

    $existsFirst = User::whereEncrypted('email', 'test@example.com')
        ->orWhereEncrypted('email', 'test4@example.com')->exists();

    expect($existsFirst)
        ->toBeTrue();
});
