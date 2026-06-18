<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\QualityEvaluation;
use Illuminate\Auth\Access\HandlesAuthorization;

class QualityEvaluationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QualityEvaluation');
    }

    public function view(AuthUser $authUser, QualityEvaluation $qualityEvaluation): bool
    {
        return $authUser->can('View:QualityEvaluation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QualityEvaluation');
    }

    public function update(AuthUser $authUser, QualityEvaluation $qualityEvaluation): bool
    {
        return $authUser->can('Update:QualityEvaluation');
    }

    public function delete(AuthUser $authUser, QualityEvaluation $qualityEvaluation): bool
    {
        return $authUser->can('Delete:QualityEvaluation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:QualityEvaluation');
    }

    public function restore(AuthUser $authUser, QualityEvaluation $qualityEvaluation): bool
    {
        return $authUser->can('Restore:QualityEvaluation');
    }

    public function forceDelete(AuthUser $authUser, QualityEvaluation $qualityEvaluation): bool
    {
        return $authUser->can('ForceDelete:QualityEvaluation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QualityEvaluation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QualityEvaluation');
    }

    public function replicate(AuthUser $authUser, QualityEvaluation $qualityEvaluation): bool
    {
        return $authUser->can('Replicate:QualityEvaluation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QualityEvaluation');
    }

}