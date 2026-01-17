<?php

namespace Privata\Tests;

use Privata\Tests\Stubs\User;

it('updates another field of user', function () {
    $user = User::create(['email' => 'test@example.com']);

    $user->update([
        'email' => 'test@gmail.com',
        'status' => true
    ]);

    expect($user->status)
        ->toBeTrue()
        ->and($user->email)
        ->toBe('test@gmail.com');
});
