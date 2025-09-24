<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(Role $role): bool
    {
        return $this->roles->contains($role);
    }

    public function assignRole(Role $role): void
    {
        if (! $this->hasRole($role)) {
            $this->roles()->attach($role);
        }
    }

    public function removeRole(Role $role): void
    {
        if ($this->hasRole($role)) {
            $this->roles()->detach($role);
        }
    }

    public function getPermissions()
    {
        $permissions = new Collection();
        foreach ($this->roles as $role) {
            if(!$permissions->contains($permissions)) {
                $permissions->push($permission);
            }
        }
        return $permissions;
    }

    public function hasPermission(Permission $permission): bool
    {
        return $this->roles->contains(
            fn (Role $role) => $role->hasPermission($permission)
        );
    }
    



    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
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
}
