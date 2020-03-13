<?php

namespace Ciliatus\Automation\Models;

use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStartTimeCondition extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'start_at', 'start_after_min_interval_asap'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * @return BelongsTo
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return '';
    }

}
