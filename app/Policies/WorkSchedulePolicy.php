<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Auth\Access\Response;

class WorkSchedulePolicy
{
    /**
     * Permite acesso total ao admin.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('work_schedules.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkSchedule $workSchedule): bool
    {
        return $user->can('work_schedules.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('work_schedules.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkSchedule $workSchedule): bool
    {
        return $user->can('work_schedules.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkSchedule $workSchedule): bool
    {
        return $user->can('work_schedules.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkSchedule $workSchedule): bool
    {
        return $user->can('work_schedules.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkSchedule $workSchedule): bool
    {
        return $user->can('work_schedules.forceDelete');
    }
}
