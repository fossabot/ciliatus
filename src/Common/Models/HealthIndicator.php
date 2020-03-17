<?php

namespace Ciliatus\Common\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HealthIndicator extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'health_indicator_type_id', 'belongsToModel_id', 'belongsToModel_type',
        'name', 'state', 'state_text', 'value'
    ];

    /**
     * @return BelongsTo
     */
    public function health_indicator_type(): BelongsTo
    {
        return $this->belongsTo(HealthIndicatorType::class, 'health_indicator_type_id');
    }

    /**
     * @return MorphTo
     */
    public function belongsToModel(): MorphTo
    {
        return $this->morphTo('belongsToModel');
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->type->getIcon();
    }

}
