<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'farm_id',
        'coffee_variety_id',
        'planting_date',
        'estimated_harvest_date',
        'area_hectares',
        'plant_count',
        'status',
        'notes',
    ];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function coffeeVariety(): BelongsTo
    {
        return $this->belongsTo(CoffeeVariety::class);
    }

    public function harvests(): HasMany
    {
        return $this->hasMany('App\Models\Harvest');
    }
}
