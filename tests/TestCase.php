<?php

namespace Privata\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Privata\PrivataServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email_encrypted', 200);
            $table->string('email_bindex', 64);
            $table->timestamp('email_encrypted_at')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app): array
    {
        return [
            PrivataServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        config()->set('privata.drivers.aes.key', 'secret');
    }
}