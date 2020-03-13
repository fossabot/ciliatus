<?php

namespace Ciliatus\Automation\Models;

use Ciliatus\Common\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplianceTypeState extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'icon', 'is_appliance_on', 'has_level', 'appliance_type_id'
    ];

    protected $casts = [
        'has_level' => 'boolean'
    ];

    /**
     * @return BelongsTo
     */
    public function appliance_type(): BelongsTo
    {
        return $this->belongsTo(ApplianceType::class, 'appliance_type_id');
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon ?? '';
    }

}
