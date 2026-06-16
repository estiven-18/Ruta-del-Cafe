<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'total_area_hectares',
        'notes',
    ];

    public function producers(): BelongsToMany
    {
        return $this->belongsToMany(Producer::class);
    }
}
