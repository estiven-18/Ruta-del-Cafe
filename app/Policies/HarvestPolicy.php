<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Harvest;
use Illuminate\Auth\Access\HandlesAuthorization;

class HarvestPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Harvest');
    }

    public function view(AuthUser $authUser, Harvest $harvest): bool
    {
        return $authUser->can('View:Harvest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Harvest');
    }

    public function update(AuthUser $authUser, Harvest $harvest): bool
    {
        return $authUser->can('Update:Harvest');
    }

    public function delete(AuthUser $authUser, Harvest $harvest): bool
    {
        return $authUser->can('Delete:Harvest');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Harvest');
    }

}