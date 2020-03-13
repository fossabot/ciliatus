<?php

namespace Ciliatus\Core\Models;

use Ciliatus\Common\Models\Model;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HabitatType extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @return bool|null
     * @throws Exception
     */
    public function delete(): ?bool
    {
        $this->habitats()->delete();
        return parent::delete();
    }

    /**
     * @return HasMany
     */
    public function habitats(): HasMany
    {
        return $this->hasMany(Habitat::class);
    }

}
