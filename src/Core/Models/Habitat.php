<?php

namespace Ciliatus\Core\Models;

use Ciliatus\Automation\Models\ApplianceGroup;
use Ciliatus\Automation\Traits\HasAppliancesTrait;
use Ciliatus\Common\Models\Model;
use Ciliatus\Monitoring\Traits\HasMonitorTrait;
use Ciliatus\Monitoring\Traits\HasSensorsTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habitat extends Model
{

    use HasAppliancesTrait, HasSensorsTrait, HasMonitorTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'habitat_type_id', 'location_id',
        'is_alert_broadcasting_enabled_warning', 'is_alert_broadcasting_enabled_critical',
        'width', 'height', 'depth'
    ];

    /**
     * @var array
     */
    protected ?array $transformable = [
        'name', 'is_active',
        'habitat_type_id', 'location_id',
        'width', 'height', 'depth',
        '_monitor'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_monitor_refresh_queued' => 'boolean',
        'is_alert_broadcasting_enabled_warning' => 'boolean',
        'is_alert_broadcasting_enabled_critical' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_monitor_refresh_at'
    ];

    /**
     * @var array
     */
    protected $with = [
        'habitat_type'
    ];

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete(): ?bool
    {
        $this->animals->each(function(Animal $animal) {
            $animal->save(['habitat_id' => null]);
        });

        return parent::delete();
    }

    /**
     * @return BelongsTo
     */
    public function habitat_type(): BelongsTo
    {
        return $this->belongsTo(HabitatType::class, 'habitat_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * @return HasMany
     */
    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class, 'habitat_id');
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return 'mdi-application';
    }

    /**
     * @return Model
     */
    public function enrich(): Model
    {
        $this->enrichMonitor();

        return parent::enrich();
    }

}
