<?php

namespace Ciliatus\Core\Models;

use Ciliatus\Common\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AnimalLitter extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'species_name',
        'conception_at',
        'birth_at'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'conception_at',
        'birth_at'
    ];

    /**
     * @return BelongsToMany
     */
    public function animals(): BelongsToMany
    {
        return $this->belongsToMany(Animal::class);
    }

    /**
     * @param string $value
     */
    public function setNameAttribute(string $value = ''): void
    {
        if ($value != '') {
            $this->attributes['name'] = $value;
            return;
        }

        if (is_null($this->animals->first())) {
            $this->attributes['name'] = Carbon::now()->format('c');
            return;
        }

        $this->attributes['name'] = sprintf(
            '%s [%s] (%s) - %s',
            $this->getSiblingGroupType(),
            $this->species_name,
            implode('+', $this->getParentNames()),
            $this->birth_at->format('c')
        );
    }

    /**
     * @return string
     */
    protected function getSiblingGroupType(): string
    {
        if (is_null($this->animals)) return trans('animal.litter.litter');
        return $this->animals->first()->class->name->litter_name;
    }

    /**
     * @return array
     */
    protected function getParentNames(): array
    {
        return $this->animals->map(fn($a) => $a->name);
    }

}
