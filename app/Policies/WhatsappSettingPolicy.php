<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WhatsappSetting;
use Illuminate\Auth\Access\HandlesAuthorization;

class WhatsappSettingPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WhatsappSetting');
    }

    public function view(AuthUser $authUser, WhatsappSetting $whatsappSetting): bool
    {
        return $authUser->can('View:WhatsappSetting');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WhatsappSetting');
    }

    public function update(AuthUser $authUser, WhatsappSetting $whatsappSetting): bool
    {
        return $authUser->can('Update:WhatsappSetting');
    }

    public function delete(AuthUser $authUser, WhatsappSetting $whatsappSetting): bool
    {
        return $authUser->can('Delete:WhatsappSetting');
    }

    public function restore(AuthUser $authUser, WhatsappSetting $whatsappSetting): bool
    {
        return $authUser->can('Restore:WhatsappSetting');
    }

    public function forceDelete(AuthUser $authUser, WhatsappSetting $whatsappSetting): bool
    {
        return $authUser->can('ForceDelete:WhatsappSetting');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WhatsappSetting');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WhatsappSetting');
    }

    public function replicate(AuthUser $authUser, WhatsappSetting $whatsappSetting): bool
    {
        return $authUser->can('Replicate:WhatsappSetting');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WhatsappSetting');
    }

}