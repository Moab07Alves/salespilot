<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Enums\AddressType;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'type',
        'city_id',
        'district',
        'street',
        'zip_code',
        'number',
        'complement',
        'is_current',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'type' => AddressType::class,
    ];

    /**
     * Relacionamento polimórfico
     * Pode pertencer a User, Cliente, Fornecedor, etc.
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Endereço pertence a uma cidade
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
