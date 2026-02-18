<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TeachingJournal;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeachingJournalPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TeachingJournal');
    }

    public function view(AuthUser $authUser, TeachingJournal $teachingJournal): bool
    {
        return $authUser->can('View:TeachingJournal');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TeachingJournal');
    }

    public function update(AuthUser $authUser, TeachingJournal $teachingJournal): bool
    {
        return $authUser->can('Update:TeachingJournal');
    }

    public function delete(AuthUser $authUser, TeachingJournal $teachingJournal): bool
    {
        return $authUser->can('Delete:TeachingJournal');
    }

    public function restore(AuthUser $authUser, TeachingJournal $teachingJournal): bool
    {
        return $authUser->can('Restore:TeachingJournal');
    }

    public function forceDelete(AuthUser $authUser, TeachingJournal $teachingJournal): bool
    {
        return $authUser->can('ForceDelete:TeachingJournal');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TeachingJournal');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TeachingJournal');
    }

    public function replicate(AuthUser $authUser, TeachingJournal $teachingJournal): bool
    {
        return $authUser->can('Replicate:TeachingJournal');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TeachingJournal');
    }

}