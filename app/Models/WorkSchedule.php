<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WorkSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'workload_minutes',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'workload_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Escalas vinculadas
     */
    public function employees()
    {
        return $this->hasMany(EmployeeWorkSchedule::class);
    }

    /**
     * Apenas ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Apenas inativos
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Carga horária formatada
     */
    public function formattedWorkload(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hours = floor($this->workload_minutes / 60);
                $minutes = $this->workload_minutes % 60;

                return sprintf('%02d:%02d', $hours, $minutes);
            }
        );
    }

    /**
     * Intervalo válido
     */
    public function isValidTimeRange(): bool
    {
        return $this->start_time < $this->end_time;
    }
}
