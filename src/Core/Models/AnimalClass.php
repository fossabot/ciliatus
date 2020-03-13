<?php

namespace Ciliatus\Core\Models;

use Ciliatus\Common\Models\Model;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnimalClass extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'litter_name'
    ];

    /**
     * @return bool|null
     * @throws Exception
     */
    public function delete(): ?bool
    {
        $this->animal_species()->delete();
        return parent::delete();
    }

    /**
     * @return HasMany
     */
    public function animal_species(): HasMany
    {
        return $this->hasMany(AnimalSpecies::class);
    }

}
