<?php

namespace Ciliatus\Automation\Enum;

use Ciliatus\Common\Enum\Enum;

/**
 * @method static ApplianceStateEnum STATE_ACTIVE
 * @method static ApplianceStateEnum STATE_INACTIVE
 * @method static ApplianceStateEnum STATE_ERROR
 * @method static ApplianceStateEnum STATE_UNKNOWN
 */
class ApplianceStateEnum extends Enum
{

    private const STATE_ACTIVE = 'active';
    private const STATE_INACTIVE = 'inactive';
    private const STATE_ERROR = 'error';
    private const STATE_UNKNOWN = 'unknown';

}
