<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\HarvestCost;
use Illuminate\Auth\Access\HandlesAuthorization;

class HarvestCostPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HarvestCost');
    }

    public function view(AuthUser $authUser, HarvestCost $harvestCost): bool
    {
        return $authUser->can('View:HarvestCost');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HarvestCost');
    }

    public function update(AuthUser $authUser, HarvestCost $harvestCost): bool
    {
        return $authUser->can('Update:HarvestCost');
    }

    public function delete(AuthUser $authUser, HarvestCost $harvestCost): bool
    {
        return $authUser->can('Delete:HarvestCost');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:HarvestCost');
    }

    public function restore(AuthUser $authUser, HarvestCost $harvestCost): bool
    {
        return $authUser->can('Restore:HarvestCost');
    }

    public function forceDelete(AuthUser $authUser, HarvestCost $harvestCost): bool
    {
        return $authUser->can('ForceDelete:HarvestCost');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HarvestCost');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HarvestCost');
    }

    public function replicate(AuthUser $authUser, HarvestCost $harvestCost): bool
    {
        return $authUser->can('Replicate:HarvestCost');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HarvestCost');
    }

}