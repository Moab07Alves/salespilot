<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HasSlug;

class Category extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relação N:1 com categoria pai
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relação 1:N com categorias filhas
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Relação recursiva para árvore completa
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Relação N:N com produtos
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    /**
     * Scope para categorias ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para categorias raiz
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope para ordenação padrão
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')
                     ->orderBy('name');
    }

    /**
     * Verifica se a categoria possui filhos
     */
    public function isParent(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Verifica se a categoria é filha
     */
    public function isChild(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Acessor: caminho completo da categoria (breadcrumb)
     */
    public function getPathAttribute(): string
    {
        return $this->parent
            ? $this->parent->path . ' > ' . $this->name
            : $this->name;
    }

    /**
     * Define os campos usados para gerar o slug
     */
    public function getSlugSource(): array
    {
        return ['name'];
    }
}
