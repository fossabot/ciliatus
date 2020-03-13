<?php

namespace Ciliatus\Core\Observers;


use Ciliatus\Core\Enum\AnimalHistoryEventsEnum;
use Ciliatus\Core\Models\Animal;

class AnimalObserver
{

    /**
     * @param Animal $animal
     */
    public function created(Animal $animal): void
    {
        $animal->writeHistory(AnimalHistoryEventsEnum::ANIMAL_CREATED());
    }
}
