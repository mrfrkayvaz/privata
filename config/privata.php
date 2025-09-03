<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Encryption Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the encryption settings for personal data.
    |
    */

    'encryption' => [
        /*
        |--------------------------------------------------------------------------
        | Driver
        |--------------------------------------------------------------------------
        |
        | The encryption driver to be used. Available drivers: "aes-256-cbc",
        | "aes-256-gcm", "chacha20-poly1305". Each driver may require
        | different handling of keys and IVs.
        |
        */
        'driver' => env('PRIVATA_ENCRYPTION_DRIVER', 'aes-256-cbc'),

        /*
        |--------------------------------------------------------------------------
        | Encryption Key
        |--------------------------------------------------------------------------
        |
        | This key is used by the Privata encryption service. It should be a random,
        | 32-byte (256-bit) string to ensure maximum security. You must set this
        | value before using the package in production. For convenience, you can
        | generate a secure key using:
        |
        |   php artisan privata:key
        |
        | or with the built-in PHP helper:
        |
        |   base64_encode(random_bytes(32))
        |
        | Keep this key secret and never share it publicly. Changing the key will
        | make previously encrypted data unreadable.
        |
        */
        'key' => env('PRIVATA_ENCRYPTION_KEY'),

        /*
        |--------------------------------------------------------------------------
        | Use Random IV/Nonce
        |--------------------------------------------------------------------------
        |
        | When enabled, a new random IV (for AES-CBC) or nonce (for AES-GCM/ChaCha20)
        | is generated for each encryption operation. This is recommended and
        | ensures that the same plaintext produces different ciphertexts.
        |
        */
        'use_random_iv' => true,

        /*
        |--------------------------------------------------------------------------
        | Base64 Encoding
        |--------------------------------------------------------------------------
        |
        | Determines whether the encrypted payload should be Base64-encoded
        | before being stored in the database. Recommended: true, since most
        | databases expect text-safe values.
        |
        */
        'base64' => true,

        /*
        |--------------------------------------------------------------------------
        | Integrity Check
        |--------------------------------------------------------------------------
        |
        | When enabled, an authentication tag (HMAC or AEAD tag) will be stored
        | and verified during decryption. For AES-GCM and ChaCha20-Poly1305,
        | this is built-in. For AES-CBC, an HMAC will be added.
        |
        */
        'integrity_check' => true
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Masking Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls how personal data is masked when displayed.
    |
    */

    'masking' => [
        /*
        |--------------------------------------------------------------------------
        | Default Mask Character
        |--------------------------------------------------------------------------
        |
        | The character used to mask sensitive data.
        |
        */
        'character' => '*',

        /*
        |--------------------------------------------------------------------------
        | Masking Rules
        |--------------------------------------------------------------------------
        |
        | Define custom masking rules for different data types.
        | You can use regex patterns or specific field names.
        |
        */
        'rules' => [
            'email' => [
                'pattern' => '/email/i',
                'mask_length' => 2,
            ],
            'phone' => [
                'pattern' => '/phone/i',
                'prefix_length' => 2,
                'suffix_length' => 2,
                'middle_replacement' => '***',
            ],
            'name' => [
                'pattern' => '/name/i',
                'mask_length' => 2,
                'fixed_mask_length' => 4,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the database settings for encrypted data storage.
    |
    */

    'database' => [
        /*
        |--------------------------------------------------------------------------
        | Foreign Key Suffix
        |--------------------------------------------------------------------------
        |
        | The suffix to be used for encrypted data foreign key columns in your
        | tables. Example: if set to "_encrypted_id", a column like
        | "email_encrypted_id" will be generated.
        |
        */
        'foreign_key_suffix' => '_encrypted_id',

        /*
        |--------------------------------------------------------------------------
        | Encrypted Timestamp Suffix
        |--------------------------------------------------------------------------
        |
        | The suffix used for timestamp columns that track when a field was last
        | encrypted. For example, if this is set to "_encrypted_at", then a
        | column like "email_encrypted_at" will be used alongside the
        | "email_encrypted" column to store the encryption timestamp.
        |
        */
        'encrypted_timestamp_suffix' => '_encrypted_at'
    ],
];
