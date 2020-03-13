<?php

namespace Ciliatus\Monitoring\Models;

use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogicalSensorReading extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'logical_sensor_id',
        'reading_raw', 'reading_corrected', 'reading_applied_correction',
        'read_at'
    ];

    /**
     * @return BelongsTo
     */
    public function logical_sensor(): BelongsTo
    {
        return $this->belongsTo(LogicalSensor::class, 'logical_sensor_id');
    }

}
