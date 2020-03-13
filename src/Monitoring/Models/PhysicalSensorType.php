<?php

namespace Ciliatus\Monitoring\Models;

use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhysicalSensorType extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'vendor', 'model', 'protocol', 'icon',
        'reading_minimum', 'reading_maximum',
        'reading_type_name', 'reading_type_unit', 'reading_type_symbol',
    ];

    /**
     * @return bool
     * @throws \Exception
     */
    public function delete(): ?bool
    {
        $this->physical_sensors()->delete();
        return parent::delete();
    }

    /**
     * @return HasMany
     */
    public function physical_sensors(): HasMany
    {
        return $this->hasMany(PhysicalSensor::class);
    }

}
