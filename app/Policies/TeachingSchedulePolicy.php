<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TeachingSchedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeachingSchedulePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TeachingSchedule');
    }

    public function view(AuthUser $authUser, TeachingSchedule $teachingSchedule): bool
    {
        return $authUser->can('View:TeachingSchedule');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TeachingSchedule');
    }

    public function update(AuthUser $authUser, TeachingSchedule $teachingSchedule): bool
    {
        return $authUser->can('Update:TeachingSchedule');
    }

    public function delete(AuthUser $authUser, TeachingSchedule $teachingSchedule): bool
    {
        return $authUser->can('Delete:TeachingSchedule');
    }

    public function restore(AuthUser $authUser, TeachingSchedule $teachingSchedule): bool
    {
        return $authUser->can('Restore:TeachingSchedule');
    }

    public function forceDelete(AuthUser $authUser, TeachingSchedule $teachingSchedule): bool
    {
        return $authUser->can('ForceDelete:TeachingSchedule');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TeachingSchedule');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TeachingSchedule');
    }

    public function replicate(AuthUser $authUser, TeachingSchedule $teachingSchedule): bool
    {
        return $authUser->can('Replicate:TeachingSchedule');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TeachingSchedule');
    }

}