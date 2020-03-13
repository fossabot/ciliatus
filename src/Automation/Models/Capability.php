<?php

namespace Ciliatus\Automation\Models;

use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Capability extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'icon', 'affected_metric_name',
        'rises_affected_metric', 'lowers_affected_metric'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'rises_affected_metric' => 'boolean',
        'lowers_affected_metric' => 'boolean'
    ];

    /**
     * @return HasManyThrough
     */
    public function appliances(): HasManyThrough
    {
        return $this->hasManyThrough(Appliance::class, ApplianceType::class);
    }

    /**
     * @return BelongsToMany
     */
    public function appliance_groups(): BelongsToMany
    {
        return $this->belongsToMany(
            ApplianceGroup::class,
            'ciliatus_automation__appliance_group_capability_pivot',
            'capability_id',
            'appliance_group_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function appliance_types(): BelongsToMany
    {
        return $this->belongsToMany(
            ApplianceType::class,
            'ciliatus_automation__appliance_type_capability_pivot',
            'capability_id',
            'appliance_type_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function workflows(): BelongsToMany
    {
        return $this->belongsToMany(
            Workflow::class,
            'ciliatus_automation__workflow_capability_pivot',
            'capability_id',
            'workflow_id'
        );
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param $name
     * @return Capability|null
     */
    public static function findByName($name): ?self
    {
        return static::where('name', $name)->first();
    }

}
