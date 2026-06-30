<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Crop;
use Illuminate\Auth\Access\HandlesAuthorization;

class CropPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Crop');
    }

    public function view(AuthUser $authUser, Crop $crop): bool
    {
        return $authUser->can('View:Crop');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Crop');
    }

    public function update(AuthUser $authUser, Crop $crop): bool
    {
        return $authUser->can('Update:Crop');
    }

    public function delete(AuthUser $authUser, Crop $crop): bool
    {
        return $authUser->can('Delete:Crop');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Crop');
    }

}