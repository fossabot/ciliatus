<?php

namespace Ciliatus\Core\Models;

use Ciliatus\Common\Models\Model;
use Ciliatus\Common\Traits\HasHistoryTrait;
use Ciliatus\Core\Enum\AnimalRelationEnum;
use Ciliatus\Core\Enum\AnimalSexEnum;
use Ciliatus\Genetics\Models\AnimalGeneticTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Animal extends Model
{

    use HasHistoryTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'habitat_id',
        'animal_species_id',
        'animal_litter_id',
        'breeding_pair_id',
        'breeding_with_animal_id',
        'generation',
        'is_active'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'habitat_id' => 'integer',
        'animal_species_id' => 'integer',
        'animal_litter_id' => 'integer',
        'breeding_pair_id' => 'integer',
        'breeding_with_animal_id' => 'integer'
    ];

    protected $with = [
        'animal_species'
    ];

    /**
     * @return BelongsTo
     */
    public function animal_species(): BelongsTo
    {
        return $this->belongsTo(AnimalSpecies::class, 'animal_species_id');
    }

    /**
     * @return HasOneThrough
     */
    public function animal_class(): HasOneThrough
    {
        return $this->hasOneThrough(AnimalClass::class, AnimalSpecies::class);
    }

    /**
     * @return BelongsTo
     */
    public function habitat(): BelongsTo
    {
        return $this->belongsTo(Habitat::class, 'habitat_id');
    }

    /**
     * @return HasOne
     */
    public function breeding_with(): HasOne
    {
        return $this->hasOne(Animal::class, 'breeding_with_animal_id');
    }

    /**
     * @return BelongsTo
     */
    public function breeding_pair(): BelongsTo
    {
        return $this->belongsTo(AnimalBreedingPair::class);
    }

    /**
     * @return BelongsToMany
     */
    public function related_animals(): BelongsToMany
    {
        return $this->belongsToMany(Animal::class)
                    ->using(AnimalRelation::class)
                    ->withPivot([
                        'relation_type'
                    ]);
    }

    /**
     * @return BelongsToMany
     */
    public function siblings(): BelongsToMany
    {
        return $this->related_animals()->where('relation_type', AnimalRelationEnum::RELATION_SIBLING());
    }

    /**
     * @return BelongsToMany
     */
    public function litter_mates(): BelongsToMany
    {
        return $this->siblings()->where('litter_id', $this->litter_id);
    }

    /**
     * @return BelongsTo
     */
    public function litter(): BelongsTo
    {
        return $this->belongsTo(AnimalLitter::class);
    }

    /**
     * @return BelongsToMany
     */
    public function mother(): BelongsToMany
    {
        return $this->related_animals()->where('relation_type', AnimalRelationEnum::RELATION_MOTHER());
    }

    /**
     * @return BelongsToMany
     */
    public function father(): BelongsToMany
    {
        return $this->related_animals()->where('relation_type', AnimalRelationEnum::RELATION_FATHER());
    }

    /**
     * @return BelongsToMany
     */
    public function parents(): BelongsToMany
    {
        return $this->siblings()->whereIn('relation_type', [AnimalRelationEnum::RELATION_MOTHER(), AnimalRelationEnum::RELATION_FATHER()]);
    }

    /**
     * @return BelongsToMany
     */
    public function children(): BelongsToMany
    {
        return $this->related_animals()->where('relation_type', AnimalRelationEnum::RELATION_CHILD());
    }

    /**
     * @param Animal $parent
     * @param AnimalSexEnum $parent_sex
     * @return Animal
     */
    public function setParent(Animal $parent, AnimalSexEnum $parent_sex = null): Animal
    {
        $parent->children()->attach($this);

        $parent_sex = $parent_sex ?? $parent->sex;

        switch ($parent_sex) {
            case AnimalSexEnum::MALE():
                $this->father()->detach();
                $this->father()->attach($parent, [ 'relation_type' => AnimalRelationEnum::RELATION_FATHER() ]);
                break;
            case AnimalSexEnum::FEMALE():
                $this->mother()->detach();
                $this->mother()->attach($parent, [ 'relation_type' => AnimalRelationEnum::RELATION_MOTHER() ]);
                break;
            default:
                $this->parents()->detach();
                $this->parents()->attach($parent, [ 'relation_type' => AnimalRelationEnum::RELATION_PARENT() ]);
        }

        $this->generation = $this->getCalculatedGeneration();

        return $this;
    }

    /**
     * @param Animal $father
     * @return Animal
     */
    public function setFather(Animal $father): Animal
    {
        return $this->setParent($father, AnimalSexEnum::MALE());
    }

    /**
     * @param Animal $mother
     * @return Animal
     */
    public function setMother(Animal $mother): Animal
    {
        return $this->setParent($mother, AnimalSexEnum::FEMALE());
    }

    /**
     * @param Animal $child
     * @param AnimalSexEnum $sex
     * @return Animal
     */
    public function addChild(Animal $child, AnimalSexEnum $sex = null): Animal
    {
        return $child->setParent($this, $sex);
    }

    /**
     * @param Animal $parent
     * @return Animal
     */
    public function removeParent(Animal $parent): Animal
    {
        $parent->children()->detach($this);
        $this->parents()->detach($parent);

        return $this;
    }

    /**
     * @param Animal $child
     * @return Animal
     */
    public function removeChild(Animal $child): Animal
    {
        return $child->removeParent($this);
    }

    /**
     * @param Animal $sibling
     * @return Animal
     */
    public function addSibling(Animal $sibling): Animal
    {
        $this->siblings()->attach($sibling, [ 'relation_type' => AnimalRelationEnum::RELATION_SIBLING() ]);

        return $this;
    }

    /**
     * @param Animal $sibling
     * @return Animal
     */
    public function removeSibling(Animal $sibling): Animal
    {
        $this->siblings()->detach($sibling);

        return $this;
    }

    /**
     * @param Animal $partner
     * @param Habitat $habitat
     * @return Animal
     */
    public function startBreedingWith(Animal $partner, Habitat $habitat = null): Animal
    {
        $abp = AnimalBreedingPair::start($this, $partner);
        $this->breeding_pair()->associate($abp);

        if (!is_null($habitat)) {
            $this->habitat()->associate($habitat);
            $partner->habitat()->associate($habitat);
        }

        return $this;
    }

    /**
     * @param Habitat|null $animal_habitat
     * @param Habitat|null $partner_habitat
     * @return Animal
     */
    public function endBreeding(Habitat $animal_habitat = null, Habitat $partner_habitat = null): Animal
    {
        if (!is_null($this->breeding_pair())) {
            if (!is_null($animal_habitat)) {
                $this->habitat()->associate($animal_habitat);
            }

            if (!is_null($partner_habitat)) {
                $this->breeding_with->habitat()->associate($partner_habitat);
            }

            $this->breeding_pair->end();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return 'mdi-tortoise';
    }

    /**
     * @return int
     */
    private function getCalculatedGeneration(): int
    {
        $gen = 0;
        $this->parents->each(function(Animal $parent) use (&$gen) {
            $gen = $parent->generation > $gen ?? $parent->generation;
        });

        return $gen + 1;
    }

}
