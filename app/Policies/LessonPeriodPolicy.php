<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LessonPeriod;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPeriodPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LessonPeriod');
    }

    public function view(AuthUser $authUser, LessonPeriod $lessonPeriod): bool
    {
        return $authUser->can('View:LessonPeriod');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LessonPeriod');
    }

    public function update(AuthUser $authUser, LessonPeriod $lessonPeriod): bool
    {
        return $authUser->can('Update:LessonPeriod');
    }

    public function delete(AuthUser $authUser, LessonPeriod $lessonPeriod): bool
    {
        return $authUser->can('Delete:LessonPeriod');
    }

    public function restore(AuthUser $authUser, LessonPeriod $lessonPeriod): bool
    {
        return $authUser->can('Restore:LessonPeriod');
    }

    public function forceDelete(AuthUser $authUser, LessonPeriod $lessonPeriod): bool
    {
        return $authUser->can('ForceDelete:LessonPeriod');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LessonPeriod');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LessonPeriod');
    }

    public function replicate(AuthUser $authUser, LessonPeriod $lessonPeriod): bool
    {
        return $authUser->can('Replicate:LessonPeriod');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LessonPeriod');
    }

}