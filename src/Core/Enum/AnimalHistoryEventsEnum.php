<?php

namespace Ciliatus\Core\Enum;

use Ciliatus\Common\Enum\Enum;

/**
 * @method static AnimalHistoryEventsEnum ANIMAL_CREATED
 * @method static AnimalHistoryEventsEnum ANIMAL_BREEDING_STARTED
 * @method static AnimalHistoryEventsEnum ANIMAL_BREEDING_ENDED
 * @method static AnimalHistoryEventsEnum ANIMAL_BREEDING_OFFSPRING
 * @method static AnimalHistoryEventsEnum ANIMAL_BREEDING_OFFSPRING_WITH
 * @method static AnimalHistoryEventsEnum ANIMAL_BREEDING_BIRTH
 */
class AnimalHistoryEventsEnum extends Enum
{

    private const ANIMAL_CREATED = 'ciliatus.core::animal.events.created';

    private const ANIMAL_BREEDING_STARTED = 'ciliatus.core::animal.events.breeding.started';
    private const ANIMAL_BREEDING_ENDED = 'ciliatus.core::animal.events.breeding.ended';
    private const ANIMAL_BREEDING_OFFSPRING = 'ciliatus.core::animal.events.breeding.offspring';
    private const ANIMAL_BREEDING_OFFSPRING_WITH = 'ciliatus.core::animal.events.breeding.offspring_with';
    private const ANIMAL_BREEDING_BIRTH = 'ciliatus.core::animal.events.breeding.birth';

}
