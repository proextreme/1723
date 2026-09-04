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
     * Demo users and demo content are seeded only outside production; real admin
     * accounts are created with `php artisan make:filament-user`. Every step is
     * safe to run again.
     */
    public function run(): void
    {
        $this->call([
            SiteLinkSeeder::class,
            SettingSeeder::class,
        ]);

        if (app()->isProduction()) {
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
