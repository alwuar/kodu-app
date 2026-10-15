<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CatalogSeeder::class);

        $email = env('ADMIN_EMAIL', 'admin@koduattachments.mx');
        $password = env('ADMIN_PASSWORD') ?: Str::password(14, symbols: false);

        $user = User::query()->firstOrNew(['email' => $email]);

        if (! $user->exists) {
            $user->fill(['name' => 'Administrador', 'password' => Hash::make($password)])->save();
            $this->command?->info("Usuario del panel: {$email} / {$password}");
        }
    }
}
