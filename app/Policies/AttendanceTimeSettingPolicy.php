<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AttendanceTimeSetting;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceTimeSettingPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AttendanceTimeSetting');
    }

    public function view(AuthUser $authUser, AttendanceTimeSetting $attendanceTimeSetting): bool
    {
        return $authUser->can('View:AttendanceTimeSetting');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AttendanceTimeSetting');
    }

    public function update(AuthUser $authUser, AttendanceTimeSetting $attendanceTimeSetting): bool
    {
        return $authUser->can('Update:AttendanceTimeSetting');
    }

    public function delete(AuthUser $authUser, AttendanceTimeSetting $attendanceTimeSetting): bool
    {
        return $authUser->can('Delete:AttendanceTimeSetting');
    }

    public function restore(AuthUser $authUser, AttendanceTimeSetting $attendanceTimeSetting): bool
    {
        return $authUser->can('Restore:AttendanceTimeSetting');
    }

    public function forceDelete(AuthUser $authUser, AttendanceTimeSetting $attendanceTimeSetting): bool
    {
        return $authUser->can('ForceDelete:AttendanceTimeSetting');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AttendanceTimeSetting');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AttendanceTimeSetting');
    }

    public function replicate(AuthUser $authUser, AttendanceTimeSetting $attendanceTimeSetting): bool
    {
        return $authUser->can('Replicate:AttendanceTimeSetting');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AttendanceTimeSetting');
    }

}