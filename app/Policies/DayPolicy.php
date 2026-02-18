<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Day;
use Illuminate\Auth\Access\HandlesAuthorization;

class DayPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Day');
    }

    public function view(AuthUser $authUser, Day $day): bool
    {
        return $authUser->can('View:Day');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Day');
    }

    public function update(AuthUser $authUser, Day $day): bool
    {
        return $authUser->can('Update:Day');
    }

    public function delete(AuthUser $authUser, Day $day): bool
    {
        return $authUser->can('Delete:Day');
    }

    public function restore(AuthUser $authUser, Day $day): bool
    {
        return $authUser->can('Restore:Day');
    }

    public function forceDelete(AuthUser $authUser, Day $day): bool
    {
        return $authUser->can('ForceDelete:Day');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Day');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Day');
    }

    public function replicate(AuthUser $authUser, Day $day): bool
    {
        return $authUser->can('Replicate:Day');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Day');
    }

}