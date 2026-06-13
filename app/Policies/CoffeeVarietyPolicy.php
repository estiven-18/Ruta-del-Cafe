<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CoffeeVariety;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoffeeVarietyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CoffeeVariety');
    }

    public function view(AuthUser $authUser, CoffeeVariety $coffeeVariety): bool
    {
        return $authUser->can('View:CoffeeVariety');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CoffeeVariety');
    }

    public function update(AuthUser $authUser, CoffeeVariety $coffeeVariety): bool
    {
        return $authUser->can('Update:CoffeeVariety');
    }

    public function delete(AuthUser $authUser, CoffeeVariety $coffeeVariety): bool
    {
        return $authUser->can('Delete:CoffeeVariety');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CoffeeVariety');
    }

    public function restore(AuthUser $authUser, CoffeeVariety $coffeeVariety): bool
    {
        return $authUser->can('Restore:CoffeeVariety');
    }

    public function forceDelete(AuthUser $authUser, CoffeeVariety $coffeeVariety): bool
    {
        return $authUser->can('ForceDelete:CoffeeVariety');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CoffeeVariety');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CoffeeVariety');
    }

    public function replicate(AuthUser $authUser, CoffeeVariety $coffeeVariety): bool
    {
        return $authUser->can('Replicate:CoffeeVariety');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CoffeeVariety');
    }

}