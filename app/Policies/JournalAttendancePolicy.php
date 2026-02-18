<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JournalAttendance;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalAttendancePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JournalAttendance');
    }

    public function view(AuthUser $authUser, JournalAttendance $journalAttendance): bool
    {
        return $authUser->can('View:JournalAttendance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JournalAttendance');
    }

    public function update(AuthUser $authUser, JournalAttendance $journalAttendance): bool
    {
        return $authUser->can('Update:JournalAttendance');
    }

    public function delete(AuthUser $authUser, JournalAttendance $journalAttendance): bool
    {
        return $authUser->can('Delete:JournalAttendance');
    }

    public function restore(AuthUser $authUser, JournalAttendance $journalAttendance): bool
    {
        return $authUser->can('Restore:JournalAttendance');
    }

    public function forceDelete(AuthUser $authUser, JournalAttendance $journalAttendance): bool
    {
        return $authUser->can('ForceDelete:JournalAttendance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JournalAttendance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JournalAttendance');
    }

    public function replicate(AuthUser $authUser, JournalAttendance $journalAttendance): bool
    {
        return $authUser->can('Replicate:JournalAttendance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JournalAttendance');
    }

}