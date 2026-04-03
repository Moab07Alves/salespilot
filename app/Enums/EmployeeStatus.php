<?php

namespace App\Enums;

enum EmployeeStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case TERMINATED = 'terminated';

    /**
     * Retorna um label amigável para exibição
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::INACTIVE => 'Inativo',
            self::TERMINATED => 'Desligado',
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
     * Ex: ['active' => 'Ativo', ...]
     */
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->label();
            return $carry;
        }, []);
    }

    /**
     * Verifica se está ativo
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Verifica se está inativo
     */
    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }

    /**
     * Verifica se está desligado
     */
    public function isTerminated(): bool
    {
        return $this === self::TERMINATED;
    }
}
