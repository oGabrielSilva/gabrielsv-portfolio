<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'gabriel04gh1.gh@gmail.com');
        $password = env('ADMIN_PASSWORD');

        if (! $password) {
            $this->command->error('Defina ADMIN_PASSWORD no .env antes de rodar o seeder.');

            return;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Gabriel'),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Admin user pronto: {$user->email}");
    }
}
