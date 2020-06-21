<?php

namespace Ciliatus\Monitoring\Models;

use Carbon\Carbon;
use Ciliatus\Common\Models\Model;
use Ciliatus\Monitoring\Jobs\RefreshMonitorHistoryJob;
use Ciliatus\Monitoring\Jobs\RefreshMonitorJob;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LogicalSensor extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'physical_sensor_id', 'logical_sensor_type_id', 'state', 'state_text',
        'current_reading_raw', 'current_reading_corrected', 'reading_correction'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_in_batch_mode' => 'boolean'
    ];

    protected $with = [
        'logical_sensor_type'
    ];

    /**
     * @return BelongsTo
     */
    public function logical_sensor_type(): BelongsTo
    {
        return $this->belongsTo(LogicalSensorType::class, 'logical_sensor_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function physical_sensor(): BelongsTo
    {
        return $this->belongsTo(PhysicalSensor::class, 'physical_sensor_id');
    }

    /**
     * @return HasMany
     */
    public function readings(): HasMany
    {
        return $this->hasMany(LogicalSensorReading::class, 'logical_sensor_id');
    }

    /**
     * @return Model|null
     */
    public function getAffectedModel(): ?Model
    {
        return $this->physical_sensor->belongsToModel;
    }

    /**
     * @param float $value
     * @param Carbon $read_at
     * @return LogicalSensorReading
     */
    public function addReading(float $value, Carbon $read_at): LogicalSensorReading
    {
        /** @var LogicalSensorReading $reading */
        $reading = $this->readings()->create([
            'reading_raw' => $value,
            'reading_corrected' => is_null($this->reading_correction) ? $value : $value + $this->reading_correction,
            'reading_applied_correction' => $this->reading_correction,
            'read_at' => $read_at
        ]);

        $this->current_reading_raw = $reading->reading_raw;
        $this->current_reading_corrected = $reading->reading_corrected;
        $this->save();

        $this->queueAffectedModelRefresh();

        return $reading;
    }

    /**
     *
     */
    public function queueAffectedModelRefresh(): LogicalSensor
    {
        if (!$this->isAffectedModelRefreshQueued() && !is_null($this->getAffectedModel()) && !$this->is_in_batch_mode) {
            dispatch(new RefreshMonitorJob($this->getAffectedModel()))->onQueue('ciliatus::monitor_refresh_queue');
            dispatch(new RefreshMonitorHistoryJob($this->getAffectedModel()))->onQueue('ciliatus::monitor_history_refresh_queue');
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isAffectedModelRefreshQueued(): bool
    {
        return is_null($this->getAffectedModel()) ? false : $this->getAffectedModel()->is_monitor_refresh_queued;
    }

    /**
     * @return LogicalSensor
     */
    public function enterBatchMode(): LogicalSensor
    {
        $this->is_in_batch_mode = true;
        $this->save();

        return $this;
    }

    /**
     * @return LogicalSensor
     */
    public function endBatchMode(): LogicalSensor
    {
        $this->is_in_batch_mode = false;
        $this->save();
        $this->queueAffectedModelRefresh();

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return 'mdi-timeline-outline';
    }

}
