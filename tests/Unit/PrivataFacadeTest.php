<?php

namespace Privata\Tests;

use Privata\Facades\Privata;

it('encrypts and decrypts values', function () {
    $value = 'lorem ipsum';

    $encrypted = Privata::encrypt($value);
    $decrypted = Privata::decrypt($encrypted);

    expect($encrypted)->not->toBe($value)
        ->and($decrypted)->toBe($value);
});

it('produces different ciphertext for same plaintext', function () {
    $value = 'lorem ipsum';
    $encrypted1 = Privata::encrypt($value);
    $encrypted2 = Privata::encrypt($value);

    expect($encrypted1)->not->toBe($encrypted2);
});