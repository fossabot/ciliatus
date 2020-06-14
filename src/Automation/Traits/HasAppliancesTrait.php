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



    /**
     * This method creates (if required) and returns the builtin appliance group for this model.
     * Usually builtin groups are automatically created by Ciliatus\Automation\Observers\HabitatObserver on model creation,
     * but if for some reason this group is deleted this method will safely create a unique builtin group.
     *
     * @return ApplianceGroup
     */
    public function builtinApplianceGroup(): ApplianceGroup
    {
        if (is_null($g = $this->appliance_groups()->where('is_builtin', true)->first())) {
            $g = $this->appliance_groups()->create([
                'name' => trans('ciliatus.automation::appliance.group.ungrouped'),
                'is_builtin' => true
            ]);
        }

        return $g;
    }

}
