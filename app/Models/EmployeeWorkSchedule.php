<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class EmployeeWorkSchedule extends Model
{
    use HasFactory;

    protected $table = 'employee_work_schedules';

    protected $fillable = [
        'employee_id',
        'work_schedule_id',
        'day_of_week',
    ];

    protected $casts = [
        'employee_id' => 'integer',
        'work_schedule_id' => 'integer',
        'day_of_week' => DayOfWeek::class,
    ];

    /**
     * Funcionário
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Escala
     */
    public function workSchedule()
    {
        return $this->belongsTo(WorkSchedule::class);
    }

    /**
     * Filtrar por dia
     */
    public function scopeOfDay($query, DayOfWeek $day)
    {
        return $query->where('day_of_week', $day);
    }

    /**
     * Filtrar por funcionário
     */
    public function scopeOfEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Nome do dia
     */
    public function dayName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->day_of_week?->label()
        );
    }
}
