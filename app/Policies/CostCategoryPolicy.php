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

}