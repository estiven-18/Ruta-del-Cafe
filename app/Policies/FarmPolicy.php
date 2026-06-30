<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Farm;
use Illuminate\Auth\Access\HandlesAuthorization;

class FarmPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Farm');
    }

    public function view(AuthUser $authUser, Farm $farm): bool
    {
        return $authUser->can('View:Farm');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Farm');
    }

    public function update(AuthUser $authUser, Farm $farm): bool
    {
        return $authUser->can('Update:Farm');
    }

    public function delete(AuthUser $authUser, Farm $farm): bool
    {
        return $authUser->can('Delete:Farm');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Farm');
    }

}