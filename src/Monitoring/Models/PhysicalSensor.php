<?php

namespace Ciliatus\Monitoring\Models;

use Ciliatus\Common\Models\Model;
use Ciliatus\Common\Traits\HasHealthIndicatorTrait;
use Ciliatus\Core\Models\Habitat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PhysicalSensor extends Model
{

    use HasHealthIndicatorTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'physical_sensor_type_id', 'belongsToModel_type', 'belongsToModel_id',
        'position_x', 'position_y', 'position_z', 'state_text'
    ];

    /**
     * @var array
     */
    protected $with = [
        'physical_sensor_type', 'logical_sensors'
    ];

    /**
     * @return bool
     * @throws \Exception
     */
    public function delete(): ?bool
    {
        $this->logical_sensors()->delete();
        return parent::delete();
    }

    /**
     * @return MorphTo
     */
    public function belongsToModel(): MorphTo
    {
        return $this->morphTo('belongsToModel', 'belongsToModel_type', 'belongsToModel_id');
    }

    /**
     * @return MorphTo
     */
    public function habitats(): MorphTo
    {
        return $this->belongsToModel()->where('belongsToModel_type', Habitat::class);
    }

    /**
     * @return BelongsTo
     */
    public function physical_sensor_type(): BelongsTo
    {
        return $this->belongsTo(PhysicalSensorType::class, 'physical_sensor_type_id');
    }

    /**
     * @return HasMany
     */
    public function logical_sensors(): HasMany
    {
        return $this->hasMany(LogicalSensor::class, 'physical_sensor_id');
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return 'mdi-network-outline';
    }

}
