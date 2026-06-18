<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualityEvaluation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'harvest_id',
        'evaluated_by',
        'evaluation_date',
        'aroma_score',
        'flavor_score',
        'acidity_score',
        'body_score',
        'sweetness_score',
        'final_score',
        'quality_grade',
        'notes',
    ];

    public function harvest(): BelongsTo
    {
        return $this->belongsTo(Harvest::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
