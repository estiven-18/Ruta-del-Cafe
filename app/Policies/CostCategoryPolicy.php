<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CostCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CostCategory');
    }

    public function view(AuthUser $authUser, CostCategory $costCategory): bool
    {
        return $authUser->can('View:CostCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CostCategory');
    }

    public function update(AuthUser $authUser, CostCategory $costCategory): bool
    {
        return $authUser->can('Update:CostCategory');
    }

    public function delete(AuthUser $authUser, CostCategory $costCategory): bool
    {
        return $authUser->can('Delete:CostCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CostCategory');
    }

    public function restore(AuthUser $authUser, CostCategory $costCategory): bool
    {
        return $authUser->can('Restore:CostCategory');
    }

    public function forceDelete(AuthUser $authUser, CostCategory $costCategory): bool
    {
        return $authUser->can('ForceDelete:CostCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CostCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CostCategory');
    }

    public function replicate(AuthUser $authUser, CostCategory $costCategory): bool
    {
        return $authUser->can('Replicate:CostCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CostCategory');
    }

}