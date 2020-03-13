<?php

namespace Ciliatus\Core\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AnimalRelation extends Pivot
{

    protected $foreignKey = 'animal_is_id';

    protected $relatedKey = 'animal_of_id';

}
