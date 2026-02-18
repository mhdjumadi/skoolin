<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RfidMaster;
use Illuminate\Auth\Access\HandlesAuthorization;

class RfidMasterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RfidMaster');
    }

    public function view(AuthUser $authUser, RfidMaster $rfidMaster): bool
    {
        return $authUser->can('View:RfidMaster');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RfidMaster');
    }

    public function update(AuthUser $authUser, RfidMaster $rfidMaster): bool
    {
        return $authUser->can('Update:RfidMaster');
    }

    public function delete(AuthUser $authUser, RfidMaster $rfidMaster): bool
    {
        return $authUser->can('Delete:RfidMaster');
    }

    public function restore(AuthUser $authUser, RfidMaster $rfidMaster): bool
    {
        return $authUser->can('Restore:RfidMaster');
    }

    public function forceDelete(AuthUser $authUser, RfidMaster $rfidMaster): bool
    {
        return $authUser->can('ForceDelete:RfidMaster');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RfidMaster');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RfidMaster');
    }

    public function replicate(AuthUser $authUser, RfidMaster $rfidMaster): bool
    {
        return $authUser->can('Replicate:RfidMaster');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RfidMaster');
    }

}