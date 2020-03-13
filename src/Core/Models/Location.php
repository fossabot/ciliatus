<?php

namespace Ciliatus\Core\Models;

use Ciliatus\Automation\Traits\HasAppliancesTrait;
use Ciliatus\Common\Models\Model;
use Ciliatus\Monitoring\Traits\HasMonitorTrait;
use Ciliatus\Monitoring\Traits\HasSensorsTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{

    use HasAppliancesTrait, HasSensorsTrait, HasMonitorTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'location_type_id'
    ];

    /**
     * @var array
     */
    protected ?array $transformable = [
        'name', 'location_type_id',
        '_monitor'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_monitor_refresh_at'
    ];

    protected $with = [
        'type'
    ];

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(LocationType::class, 'location_type_id');
    }

    /**
     * @return HasMany
     */
    public function habitats(): HasMany
    {
        return $this->hasMany(Habitat::class, 'location_id');
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return 'mdi-map-marker-outline';
    }

    /**
     * @return Model
     */
    public function enrich(): Model
    {
        $this->renderMonitor();

        return parent::enrich();
    }

}
