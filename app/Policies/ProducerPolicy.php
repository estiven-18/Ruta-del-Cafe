<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Producer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProducerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Producer');
    }

    public function view(AuthUser $authUser, Producer $producer): bool
    {
        return $authUser->can('View:Producer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Producer');
    }

    public function update(AuthUser $authUser, Producer $producer): bool
    {
        return $authUser->can('Update:Producer');
    }

    public function delete(AuthUser $authUser, Producer $producer): bool
    {
        return $authUser->can('Delete:Producer');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Producer');
    }

    public function restore(AuthUser $authUser, Producer $producer): bool
    {
        return $authUser->can('Restore:Producer');
    }

    public function forceDelete(AuthUser $authUser, Producer $producer): bool
    {
        return $authUser->can('ForceDelete:Producer');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Producer');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Producer');
    }

    public function replicate(AuthUser $authUser, Producer $producer): bool
    {
        return $authUser->can('Replicate:Producer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Producer');
    }

}