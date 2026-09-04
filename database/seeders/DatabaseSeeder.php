<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Site links and settings are always seeded (no secrets, just the contract).
     * Demo users and factory-generated demo content run only in the local
     * environment — `fakerphp/faker` is a dev dependency and is absent from
     * staging/production, where admin accounts are created with
     * `php artisan make:filament-user`.
     */
    public function run(): void
    {
        $this->call([
            SiteLinkSeeder::class,
            SettingSeeder::class,
        ]);

        if (! app()->environment('local', 'testing')) {
            return;
        }

        $this->firstOrCreateUser('admin@1723.test', 'Admin', fn () => User::factory()->administrator());
        $this->firstOrCreateUser('editor@1723.test', 'Editor', fn () => User::factory()->contentAdministrator());

        $this->call(DemoContentSeeder::class);
    }

    /**
     * @param  \Closure(): UserFactory  $factory
     */
    private function firstOrCreateUser(string $email, string $name, \Closure $factory): void
    {
        if (User::query()->where('email', $email)->exists()) {
            return;
        }

        $factory()->create(['name' => $name, 'email' => $email]);
    }
}
