<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MagnooliaAdminCreateCommand extends Command
{
    protected $signature = 'magnoolia:admin:create
                            {--name= : Full name}
                            {--email= : Login e-mail}
                            {--password= : Password (asked for interactively when omitted)}
                            {--role= : magnoolia_admin | magnoolia_client_admin | magnoolia_editor}';

    protected $description = 'Create a Magnoolia admin user (interactive, or fully from options)';

    /**
     * The three roles that can sign in, most privileged first.
     *
     * `magnoolia_client_admin` was missing here, which meant the role the whole
     * Phase 36 control centre is built for could not be created by this command.
     */
    private const ROLES = ['magnoolia_admin', 'magnoolia_client_admin', 'magnoolia_editor'];

    public function handle(): int
    {
        $name = trim((string) ($this->option('name') ?? '')) ?: trim((string) $this->ask('Name'));
        $email = trim((string) ($this->option('email') ?? '')) ?: trim((string) $this->ask('Email'));

        if ($password = (string) $this->option('password')) {
            $confirmation = $password;
        } else {
            $password = (string) $this->secret('Password');
            $confirmation = (string) $this->secret('Confirm password');
        }

        $role = (string) ($this->option('role') ?? '') ?: $this->choice('Role', self::ROLES, 0);

        if (! in_array($role, self::ROLES, true)) {
            $this->error('Unknown role. Use one of: ' . implode(', ', self::ROLES));
            return self::FAILURE;
        }

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

        $this->info("Magnoolia user created: {$email} ({$role}).");
        $this->line('Log in at /admin/login, then open /admin/magnoolia.');

        return self::SUCCESS;
    }
}
