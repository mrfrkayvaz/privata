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
        | The encryption driver to be used for securing sensitive data.
        | Supported: "aes"
        |
        | You may also customize how the encryption is handled by providing
        | driver-specific configuration (e.g., key length, IV handling).
        |
        */
        'driver' => env('PRIVATA_ENCRYPTION_DRIVER', 'aes'),

        /*
        |--------------------------------------------------------------------------
        | Masking Character
        |--------------------------------------------------------------------------
        |
        | The character that will be used to mask sensitive data when decrypted
        | values should not be exposed. Typically, an asterisk (*) is used,
        | but you may change this to any character of your choice.
        |
        */
        'masking_character' => '*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure all the available encryption drivers and their
    | specific settings. Each driver may require its own options such as
    | keys, ciphers, or other parameters.
    |
    | By default, the "aes" driver is available, but you may add custom
    | drivers here if your application requires different algorithms.
    |
    */
    'drivers' => [
        /*
        |--------------------------------------------------------------------------
        | AES Driver
        |--------------------------------------------------------------------------
        |
        | Configuration options for the AES encryption driver.
        | - "key" should be a secure, random key (usually pulled from env).
        | - "cipher" defines the AES variant (e.g., aes-256-cbc).
        |
        */
        'aes' => [
            'key' => env('PRIVATA_ENCRYPTION_KEY'),
            'cipher' => env('PRIVATA_ENCRYPTION_CIPHER', 'aes-256-cbc'),
        ]
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
        | Encrypted Data Suffix
        |--------------------------------------------------------------------------
        |
        | The suffix appended to attributes that store the encrypted value.
        | Example: "email" becomes "email_encrypted".
        |
        */
        'encrypted_data_suffix' => '_encrypted',

        /*
        |--------------------------------------------------------------------------
        | Use Encrypted Timestamp
        |--------------------------------------------------------------------------
        |
        | When set to true, the package will automatically update the timestamp
        | column (defined by encrypted_timestamp_suffix) whenever an
        | attribute is encrypted or updated.
        |
        */
        'use_encrypted_timestamp' => true,

        /*
        |--------------------------------------------------------------------------
        | Encrypted Timestamp Suffix
        |--------------------------------------------------------------------------
        |
        | The suffix appended to attributes that track when the value was last
        | encrypted. Example: "email" becomes "email_encrypted_at".
        |
        */
        'encrypted_timestamp_suffix' => '_encrypted_at',

        /*
        |--------------------------------------------------------------------------
        | Encrypted Masked Suffix
        |--------------------------------------------------------------------------
        |
        | The suffix appended to attributes that store the masked (partially
        | obfuscated) version of the decrypted value.
        | Example: "email" becomes "email_masked".
        |
        */
        'encrypted_masked_suffix' => '_masked',

        /*
        |--------------------------------------------------------------------------
        | Blind Index Pepper
        |--------------------------------------------------------------------------
        |
        | The "pepper" is a secret key used to generate keyed-hashes (HMAC) for
        | searchable encrypted data. Unlike a "salt" which is stored in the
        | database, the pepper is kept secret within your application code
        | or environment variables.
        |
        | This prevents attackers from performing rainbow table or frequency
        | analysis attacks if the database is compromised, as they would
        | still lack the secret pepper required to compute the hashes.
        |
        | CAUTION: If the pepper is changed or lost, all existing search
        | indexes (bindex fields) will become unsearchable.
        |
        */

        'pepper' => env('PRIVATA_PEPPER'),

        /*
        |--------------------------------------------------------------------------
        | Encrypted Blind Index Suffix
        |--------------------------------------------------------------------------
        |
        | The suffix appended to attributes that store the searchable hash (Blind Index).
        | This allows for exact-match lookups on encrypted data without
        | compromising the randomness of the primary encryption.
        | Example: "email" becomes "email_bindex".
        |
        */
        'encrypted_bindex_suffix' => '_bindex',

        /*
        |--------------------------------------------------------------------------
        | Add Masked Value
        |--------------------------------------------------------------------------
        |
        | Whether to automatically add a masked value field alongside the decrypted
        | attribute. When true, an extra "{attribute}_masked" field will be included.
        |
        */
        'add_masked_value' => true,
    ],
];
