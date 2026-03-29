<?php

namespace App\Enums;

enum AddressType: string
{
    case HOME = 'home';
    case WORK = 'work';
    case BILLING = 'billing';
    case SHIPPING = 'shipping';
    case OTHER = 'other';

    /**
     * Retorna um label amigável para exibição
     */
    public function label(): string
    {
        return match ($this) {
            self::HOME => 'Residencial',
            self::WORK => 'Comercial',
            self::BILLING => 'Cobrança',
            self::SHIPPING => 'Entrega',
            self::OTHER => 'Outro',
        };
    }

    /**
     * Retorna todos os valores disponíveis
     * (útil para validação e selects)
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Retorna os dados formatados para uso em select
     * Ex: ['home' => 'Residencial', ...]
     */
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->label();
            return $carry;
        }, []);
    }
}
