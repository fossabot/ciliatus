<?php

namespace Ciliatus\Core\Models;

use Ciliatus\Common\Models\Model;
use Carbon\Carbon;
use Ciliatus\Common\Traits\HasHistoryTrait;
use Ciliatus\Core\Enum\AnimalHistoryEventsEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AnimalBreedingPair extends Model
{

    use HasHistoryTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'animal_id',
        'partner_id'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'breeding_started_at',
        'breeding_ended_at'
    ];

    /**
     * @param Animal $animal
     * @param Animal $partner
     * @return AnimalBreedingPair
     */
    public static function start(Animal $animal, Animal $partner): AnimalBreedingPair
    {
        if (!is_null($animal->breeding_with)) {
            $animal->endBreeding();
        }
        if (!is_null($partner->breeding_with)) {
            $partner->endBreeding();
        }

        $animal->breeding_with_animal_id = $partner->id;
        $partner->breeding_with_animal_id = $animal->id;
        $animal->save();
        $partner->save();

        $animal->writeHistory(AnimalHistoryEventsEnum::ANIMAL_BREEDING_STARTED(), [$partner]);
        $partner->writeHistory(AnimalHistoryEventsEnum::ANIMAL_BREEDING_STARTED(), [$animal]);
    }

    /**
     *
     */
    public function end(): void
    {
        $animal = $this->animal;
        $partner = $this->partner;

        $animal->breeding_with_animal_id = null;
        $animal->save();
        $partner->breeding_with_animal_id = null;
        $partner->save();

        $animal->writeHistory(AnimalHistoryEventsEnum::ANIMAL_BREEDING_ENDED(), [$partner]);
        $partner->writeHistory(AnimalHistoryEventsEnum::ANIMAL_BREEDING_ENDED(), [$animal]);
    }

    /**
     * @param Animal $animal
     */
    public function addOffspring(Animal $animal): void
    {
        $this->animal->addChild($animal);
        $this->partner->addChild($animal);

        $this->writeHistory(AnimalHistoryEventsEnum::ANIMAL_BREEDING_OFFSPRING(), [$this->animal, $this->partner, $animal]);
        $this->animal->writeHistory(AnimalHistoryEventsEnum::ANIMAL_BREEDING_OFFSPRING_WITH(), [$animal, $this->partner]);
        $this->partner->writeHistory(AnimalHistoryEventsEnum::ANIMAL_BREEDING_OFFSPRING_WITH(), [$animal, $this->animal]);
        $animal->writeHistory(AnimalHistoryEventsEnum::ANIMAL_BREEDING_BIRTH(), [$this->animal, $this->partner]);
    }

    /**
     * @return HasOne
     */
    public function animal(): HasOne
    {
        return $this->hasOne(Animal::class, 'animal_id');
    }

    /**
     * @return HasOne
     */
    public function partner(): HasOne
    {
        return $this->hasOne(Animal::class, 'partner_id');
    }

}
