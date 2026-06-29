<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfitabilityReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'harvest_id',
        'total_income',
        'total_costs',
        'net_profit',
        'profitability_percentage',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'calculated_at' => 'datetime',
        ];
    }

    public function harvest(): BelongsTo
    {
        return $this->belongsTo(Harvest::class)->withTrashed();
    }
}
