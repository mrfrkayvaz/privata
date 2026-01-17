<?php

namespace Privata\Tests;

use Illuminate\Support\Facades\DB;
use Privata\Tests\Stubs\User;

it('updates another field of user', function () {
    $user = User::create(['email' => 'test@example.com']);

    $user->update([
        'status' => true
    ]);

    expect($user->status)
        ->toBeTrue();
});
