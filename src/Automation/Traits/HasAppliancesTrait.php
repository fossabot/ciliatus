<?php

namespace Ciliatus\Automation\Traits;

use Ciliatus\Automation\Models\ApplianceGroup;
use Ciliatus\Automation\Models\Workflow;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasAppliancesTrait
{


    /**
     * @return MorphToMany
     */
    public function appliance_groups(): MorphToMany
    {
        return $this->morphToMany(
            ApplianceGroup::class,
            'belongsToModel',
            'ciliatus_automation__appliance_group_belongs_pivot'
        );
    }


    /**
     * @return MorphToMany
     */
    public function workflows(): MorphToMany
    {
        return $this->morphToMany(
            Workflow::class,
            'belongsToModel',
            'ciliatus_automation__workflow_belongs_pivot'
        );
    }

}
