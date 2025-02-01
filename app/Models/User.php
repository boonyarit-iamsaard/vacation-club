<?php

namespace App\Models;

use App\Role;
use Database\Factories\UserFactory;
use Exception;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
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
        'role',
        'image',
        'password',
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

    public function hasAdministrativeAccess(): bool
    {
        return $this->role === Role::ADMINISTRATOR;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        try {
            return match ($panel->getId()) {
                'admin' => $this->hasAdministrativeAccess(),
                default => false,
            };
        } catch (Exception $e) {
            // TODO: standardize logging
            Log::error('Error determining panel access', [
                'user_id' => $this->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

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
            'role' => Role::class,
        ];
    }
}
