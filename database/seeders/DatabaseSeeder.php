<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\AdminSystem\AdminSystemSeeder;
use Database\Seeders\Auth\CredentialUserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CredentialUserSeeder::class,
            AdminSystemSeeder::class,
        ]);
    }
}
