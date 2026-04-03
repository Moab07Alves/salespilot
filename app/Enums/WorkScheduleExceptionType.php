<?php

namespace App\Enums;

enum WorkScheduleExceptionType: string
{
    case VACATION = 'vacation';
    case LEAVE = 'leave';
    case HOLIDAY = 'holiday';
    case DAY_OFF = 'day_off';
    case SHIFT_CHANGE = 'shift_change';

    public function label(): string
    {
        return match ($this) {
            self::VACATION => 'Férias',
            self::LEAVE => 'Afastamento',
            self::HOLIDAY => 'Feriado',
            self::DAY_OFF => 'Folga',
            self::SHIFT_CHANGE => 'Troca de turno',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->label();
            return $carry;
        }, []);
    }
}
