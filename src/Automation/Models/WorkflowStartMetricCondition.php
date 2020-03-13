<?php

namespace Ciliatus\Automation\Models;

use Carbon\Carbon;
use Ciliatus\Common\Models\Model;
use Ciliatus\Monitoring\Models\LogicalSensorType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStartMetricCondition extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'is_active',
        'workflow_id', 'logical_sensor_type_id',
        'timeframe_start', 'timeframe_end',
        'is_above', 'is_below',
        'in_state_amount', 'in_state_duration_minutes', 'in_state_bucket_size_minutes'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_above' => 'boolean',
        'is_below' => 'boolean'
    ];

    /**
     * @return BelongsTo
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    /**
     * @return BelongsTo
     */
    public function logical_sensor_type(): BelongsTo
    {
        return $this->belongsTo(LogicalSensorType::class, 'logical_sensor_type_id');
    }

    /**
     * @param Carbon|null $time
     * @return bool
     */
    public function startConditionsMet(Carbon $time = null): bool
    {
        $time = $time ?? Carbon::now();

        if (!$this->isActiveRecursive()) return false;
        if (!$this->workflow->isReadyToStart()) return false;
        if (!$this->isWithinTimeframe($time)) return false;

        $start = clone $time->subMinutes($this->in_state_duration_minutes);
        $end = clone $time;
        $lower_end = $this->is_above ? $this->in_state_amount : null;
        $upper_end = $this->is_below ? $this->in_state_amount : null;
        $stable = $this->workflow->belongsToModel->verifyHistoryForMetricStableInRange(
            $start,
            $end,
            $this->in_state_bucket_size_minutes,
            $this->logical_sensor_type_id,
            $lower_end,
            $upper_end
        );
        if (!$stable) return false;

        return true;
    }

    /**
     * @param Carbon|null $time
     * @return bool
     */
    public function isWithinTimeframe(Carbon $time = null): bool
    {
        $time = $time ?? Carbon::now();

        if (!is_null($this->timeframe_start)) {
            if (!$time->isAfter(clone $time->setTimeFromTimeString($this->timeframe_start))) {
                return false;
            }
        }

        if (!is_null($this->timeframe_end)) {
            if (!$time->isBefore(clone $time->setTimeFromTimeString($this->timeframe_start))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isActiveRecursive(): bool
    {
        return $this->is_active && $this->workflow->is_active && $this->workflow->belongsToModel->is_active;

    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return '';
    }

}
