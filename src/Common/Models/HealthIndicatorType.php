<?php

namespace Ciliatus\Common\Models;

use Ciliatus\Common\Traits\HasMultipleDataTypeFieldsTrait;

class HealthIndicatorType extends Model
{

    use HasMultipleDataTypeFieldsTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'icon'
    ];

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon ?? 'help';
    }

}
