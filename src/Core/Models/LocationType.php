<?php

namespace Ciliatus\Core\Models;

use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LocationType extends Model
{

    protected $fillable = [
        'name'
    ];

    /**
     * @return HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

}
