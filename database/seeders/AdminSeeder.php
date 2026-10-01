<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * The first administrator, from ADMIN_NAME / ADMIN_EMAIL / ADMIN_PASSWORD.
 * Skipped when an administrator with that email already exists.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $model = config('admin.auth.model');
        $email = (string) env('ADMIN_EMAIL', 'admin@example.com');

        if ($model::query()->where('email', $email)->exists()) {
            return;
        }

        Artisan::call('admin:user', [
            'name' => (string) env('ADMIN_NAME', 'Admin'),
            'email' => $email,
            'password' => (string) env('ADMIN_PASSWORD', 'password'),
            '--super' => true,
        ]);

        $this->command?->info("Administrator: {$email} (password from ADMIN_PASSWORD, default \"password\")");
    }
}
