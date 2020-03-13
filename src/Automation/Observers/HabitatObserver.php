<?php

namespace Ciliatus\Automation\Observers;

use Ciliatus\Core\Models\Habitat;

class HabitatObserver
{

    public function created(Habitat $habitat)
    {
        $habitat->appliance_groups()->create([
            'name' => trans('ciliatus.automation::appliance.group.ungrouped'),
            'is_builtin' => true
        ]);
    }
}
