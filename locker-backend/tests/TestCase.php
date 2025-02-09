<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Erstellen Sie die SQLite-Datenbankdatei, falls sie nicht existiert
        if (!file_exists(database_path('test-database.sqlite'))) {
            touch(database_path('test-database.sqlite'));
        }

        // Führen Sie die Migrationen aus
        $this->artisan('migrate');
    }
}
