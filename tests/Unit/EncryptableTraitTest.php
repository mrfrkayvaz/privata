<?php

namespace Privata\Tests;

use Privata\Tests\Stubs\User;
use Privata\Tests\Stubs\UserMasked;

it('hides encrypted data', function () {
    $user = User::create(['email' => 'test@example.com']);

    $id = $user->id ?? null;

    $hidden = $user->getHidden();

    expect($id)
        ->not->toBeNull()
        ->and($hidden)
        ->toContain('email_encrypted');
});

it('encrypts and decrypts given value', function () {
    $email = 'test@example.com';
    $user = User::create(['email' => $email]);

    expect($user->email)
        ->toBe($email);
});

it('returns masked field', function () {
    $email = 'test@example.com';
    $user = User::create(['email' => $email]);

    expect($user->email_masked)
        ->toBe('t*******@e*******.com');
});

it('denies real value when can decrypt is false', function () {
    $email = 'test@example.com';
    $user = UserMasked::create(['email' => $email]);

    expect($user->email)
        ->toBe('t*******@e*******.com');
});