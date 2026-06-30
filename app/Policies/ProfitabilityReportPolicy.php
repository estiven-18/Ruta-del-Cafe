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

}