<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['magnoolia_admin', 'magnoolia_editor'], true);
    }
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isMagnooliaAdmin(): bool
    {
        return $this->role === 'magnoolia_admin';
    }

    public function isMagnooliaEditor(): bool
    {
        return $this->role === 'magnoolia_editor';
    }

    /**
     * Phase 33.3 — the client admin role: full daily editing + publishing in the
     * Magnoolia control center, but no access to the Filament panel or advanced
     * (Translations / Languages / Navigation / Audit) sections.
     */
    public function isMagnooliaClientAdmin(): bool
    {
        return $this->role === 'magnoolia_client_admin';
    }
}
