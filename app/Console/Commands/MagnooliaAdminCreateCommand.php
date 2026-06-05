<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MagnooliaAdminCreateCommand extends Command
{
    protected $signature = 'magnoolia:admin:create';

    protected $description = 'Create Magnoolia admin user interactively';

    public function handle(): int
    {
        $name = trim((string) $this->ask('Name'));
        $email = trim((string) $this->ask('Email'));
        $password = (string) $this->secret('Password');
        $confirmation = (string) $this->secret('Confirm password');
        $role = $this->choice('Role', ['magnoolia_admin', 'magnoolia_editor'], 0);

        if ($password !== $confirmation) {
            $this->error('Password confirmation does not match.');
            return self::FAILURE;
        }

        if (User::query()->where('email', $email)->exists()) {
            $this->error('User with this email already exists.');
            return self::FAILURE;
        }

        User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
            'email_verified_at' => now(),
        ]);

        $this->info('Magnoolia admin user created successfully.');
        return self::SUCCESS;
    }
}
