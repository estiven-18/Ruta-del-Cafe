<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProfitabilityReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfitabilityReportPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProfitabilityReport');
    }

    public function view(AuthUser $authUser, ProfitabilityReport $profitabilityReport): bool
    {
        return $authUser->can('View:ProfitabilityReport');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProfitabilityReport');
    }

    public function update(AuthUser $authUser, ProfitabilityReport $profitabilityReport): bool
    {
        return $authUser->can('Update:ProfitabilityReport');
    }

    public function delete(AuthUser $authUser, ProfitabilityReport $profitabilityReport): bool
    {
        return $authUser->can('Delete:ProfitabilityReport');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProfitabilityReport');
    }

    public function restore(AuthUser $authUser, ProfitabilityReport $profitabilityReport): bool
    {
        return $authUser->can('Restore:ProfitabilityReport');
    }

    public function forceDelete(AuthUser $authUser, ProfitabilityReport $profitabilityReport): bool
    {
        return $authUser->can('ForceDelete:ProfitabilityReport');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProfitabilityReport');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProfitabilityReport');
    }

    public function replicate(AuthUser $authUser, ProfitabilityReport $profitabilityReport): bool
    {
        return $authUser->can('Replicate:ProfitabilityReport');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProfitabilityReport');
    }

}