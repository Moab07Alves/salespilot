<?php

namespace App\Models;

use App\Enums\WorkScheduleExceptionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WorkScheduleException extends Model
{
    use HasFactory;

    protected $table = 'work_schedule_exceptions';

    protected $fillable = [
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'type',
        'reason',
    ];

    protected $casts = [
        'employee_id' => 'integer',
        'date'        => 'date',
        'start_time'  => 'datetime:H:i',
        'end_time'    => 'datetime:H:i',
        'type'        => WorkScheduleExceptionType::class,
        'reason'      => 'string',
    ];

    /**
     * Funcionário
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Filtrar por funcionário
     */
    public function scopeOfEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Filtrar por data
     */
    public function scopeOfDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Filtrar por tipo
     */
    public function scopeOfType($query, WorkScheduleExceptionType $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Possui horário?
     */
    public function hasTimeRange(): bool
    {
        return $this->start_time && $this->end_time;
    }

    /**
     * Horário formatado
     */
    public function formattedTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasTimeRange()
                ? $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i')
                : null
        );
    }

    /**
     * Tipo formatado
     */
    public function typeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type?->label()
        );
    }
}
