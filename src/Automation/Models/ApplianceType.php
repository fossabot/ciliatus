<?php

namespace Ciliatus\Automation\Models;

use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplianceType extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'icon', 'model', 'vendor', 'protocol',
        'level_minimum', 'level_maximum', 'level_step_size'
    ];

    protected $with = [
        'states', 'capabilities'
    ];

    /**
     * @return HasMany
     */
    public function appliances(): HasMany
    {
        return $this->hasMany(Appliance::class, 'appliance_type_id');
    }

    /**
     * @return HasMany
     */
    public function states(): HasMany
    {
        return $this->hasMany(ApplianceTypeState::class, 'appliance_type_id');
    }

    /**
     * @return BelongsToMany
     */
    public function capabilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Capability::class,
            'ciliatus_automation__appliance_type_capability_pivot',
            'appliance_type_id',
            'capability_id'
        );
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return '';
    }

}
