<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'avatar',
        'avatar_disk',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    /**
     * Relação 1:1 com Employee
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Scope para usuários ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Acessor: verifica se é funcionário
     */
    public function isEmployee(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->employee !== null
        );
    }

    /**
     * Acessor: URL do avatar
     */
    public function avatarUrl(): Attribute
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($this->avatar_disk ?? 'public');

        return Attribute::make(
            get: fn () => $this->avatar
                ? $disk->url($this->avatar)
                : null
        );
    }
}
