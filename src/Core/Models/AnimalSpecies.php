<?php

namespace Ciliatus\Core\Models;

use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnimalSpecies extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name_common',
        'name_latin',
        'animal_class_id'
    ];

    protected $with = [
        'animal_class'
    ];

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete(): ?bool
    {
        $this->animals()->delete();
        return parent::delete();
    }

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return is_null($this->name_latin) ? $this->name_common : $this->name_latin;
    }

    /**
     * @return HasMany
     */
    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }

    /**
     * @return BelongsTo
     */
    public function animal_class(): BelongsTo
    {
        return $this->belongsTo(AnimalClass::class);
    }

}
