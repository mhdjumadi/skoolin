<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\HomeroomTeacher;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomeroomTeacherPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HomeroomTeacher');
    }

    public function view(AuthUser $authUser, HomeroomTeacher $homeroomTeacher): bool
    {
        return $authUser->can('View:HomeroomTeacher');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HomeroomTeacher');
    }

    public function update(AuthUser $authUser, HomeroomTeacher $homeroomTeacher): bool
    {
        return $authUser->can('Update:HomeroomTeacher');
    }

    public function delete(AuthUser $authUser, HomeroomTeacher $homeroomTeacher): bool
    {
        return $authUser->can('Delete:HomeroomTeacher');
    }

    public function restore(AuthUser $authUser, HomeroomTeacher $homeroomTeacher): bool
    {
        return $authUser->can('Restore:HomeroomTeacher');
    }

    public function forceDelete(AuthUser $authUser, HomeroomTeacher $homeroomTeacher): bool
    {
        return $authUser->can('ForceDelete:HomeroomTeacher');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HomeroomTeacher');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HomeroomTeacher');
    }

    public function replicate(AuthUser $authUser, HomeroomTeacher $homeroomTeacher): bool
    {
        return $authUser->can('Replicate:HomeroomTeacher');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HomeroomTeacher');
    }

}