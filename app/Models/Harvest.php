<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Harvest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'crop_id',
        'harvest_date',
        'gross_weight_kg',
        'defective_weight_kg',
        'net_weight_kg',
        'sale_price_per_kg',
        'total_income',
        'notes',
    ];

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function harvestCosts(): HasMany
    {
        return $this->hasMany('App\Models\HarvestCost');
    }

    public function qualityEvaluations(): HasMany
    {
        return $this->hasMany('App\Models\QualityEvaluation');
    }

    public function profitabilityReport(): HasOne
    {
        return $this->hasOne('App\Models\ProfitabilityReport');
    }
}
