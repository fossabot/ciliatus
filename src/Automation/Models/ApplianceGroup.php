<?php

namespace Ciliatus\Automation\Models;

use Ciliatus\Automation\Enum\ApplianceGroupStateEnum;
use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApplianceGroup extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'is_builtin', 'state', 'state_text'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_builtin' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $with = [
        'appliances', 'capabilities'
    ];

    /**
     * @return BelongsToMany
     */
    public function appliances(): BelongsToMany
    {
        return $this->belongsToMany(
            Appliance::class,
            'ciliatus_automation__appliance_appliance_group_pivot',
            'appliance_group_id',
            'appliance_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function capabilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Capability::class,
            'ciliatus_automation__appliance_group_capability_pivot',
            'appliance_group_id',
            'capability_id'
        );
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon ?? '';
    }

    /**
     * @param string $text
     * @return ApplianceGroup
     */
    public function error(string $text): self
    {
        $this->state = ApplianceGroupStateEnum::STATE_ERROR();
        $this->state_text = $text;
        $this->save();

        return $this;
    }

}
