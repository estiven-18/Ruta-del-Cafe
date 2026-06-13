<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoffeeVariety extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'scientific_name',
        'description',
        'typical_maturity_months',
        'is_resistant',
    ];
}
