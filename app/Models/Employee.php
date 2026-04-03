<?php

namespace App\Models;

use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'position_id',
        'department_id',
        'manager_id',
        'document',
        'birth_date',
        'phone',
        'hire_date',
        'termination_date',
        'salary',
        'status',
    ];

    protected $casts = [
        'birth_date'       => 'date',
        'hire_date'        => 'date',
        'termination_date' => 'date',
        'salary'           => 'decimal:2',
        'status'           => EmployeeStatus::class,
    ];

    /**
     * Usuário relacionado
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cargo do funcionário
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Departamento do funcionário
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Gerente (auto-relacionamento)
     */
    public function manager()
    {
        return $this->belongsTo(self::class, 'manager_id');
    }

    /**
     * Subordinados
     */
    public function subordinates()
    {
        return $this->hasMany(self::class, 'manager_id');
    }

    /**
     * Escalas (pivot)
     */
    public function workSchedules()
    {
        return $this->hasMany(EmployeeWorkSchedule::class);
    }

    /**
     * Schedules via pivot
     */
    public function schedules()
    {
        return $this->hasManyThrough(
            WorkSchedule::class,
            EmployeeWorkSchedule::class,
            'employee_id',
            'id',
            'id',
            'work_schedule_id'
        );
    }

    /**
     * Exceções de escala
     */
    public function exceptions()
    {
        return $this->hasMany(WorkScheduleException::class);
    }

    /**
     * Apenas funcionários ativos
     */
    public function scopeActive($query)
    {
        return $query->where('status', EmployeeStatus::ACTIVE);
    }

    /**
     * Apenas funcionários inativos
     */
    public function scopeInactive($query)
    {
        return $query->where('status', EmployeeStatus::INACTIVE);
    }

    /**
     * Verifica se é gerente
     */
    public function isManager(): bool
    {
        return $this->subordinates()->exists();
    }

    /**
     * Verifica se possui gerente
     */
    public function hasManager(): bool
    {
        return !is_null($this->manager_id);
    }

    /**
     * Verifica se está ativo
     */
    public function isActive(): bool
    {
        return $this->status?->isActive();
    }

    /**
     * Verifica se está desligado
     */
    public function isTerminated(): bool
    {
        return $this->status?->isTerminated();
    }

    /**
     * Idade do funcionário
     */
    public function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birth_date?->age
        );
    }
}
