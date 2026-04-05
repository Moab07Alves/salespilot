<?php

namespace App\Models;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Supplier extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'document',
        'email',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Endereços do fornecedor (polimórfico)
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Endereço atual
     */
    public function currentAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')
            ->where('is_current', true);
    }

    /**
     * Produtos fornecidos (N:N)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier')
            ->withPivot(['cost_price', 'supplier_sku'])
            ->withTimestamps();
    }

    /**
     * Scope para fornecedores ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para fornecedores inativos
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Verifica se está ativo
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Verifica se possui produtos vinculados
     */
    public function hasProducts(): bool
    {
        return $this->products()->exists();
    }

    /**
     * Verifica se possui endereço
     */
    public function hasAddress(): bool
    {
        return $this->addresses()->exists();
    }

    /**
     * Define os campos usados para gerar o slug
     */
    public function getSlugSource(): array
    {
        return ['name'];
    }
}
