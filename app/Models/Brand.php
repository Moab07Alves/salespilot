<?php

namespace App\Models;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Produtos desta marca
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope para marcas ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para marcas inativas
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Verifica se a marca está ativa
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Verifica se possui produtos
     */
    public function hasProducts(): bool
    {
        return $this->products()->exists();
    }

    /**
     * Define os campos usados para gerar o slug
     */
    public function getSlugSource(): array
    {
        return ['name'];
    }
}
