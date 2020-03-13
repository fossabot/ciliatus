<?php

namespace Ciliatus\Monitoring\Enum;

use Ciliatus\Common\Enum\Enum;

/**
 * @method static LogicalSensorStateEnum STATE_OK
 * @method static LogicalSensorStateEnum STATE_NOTOK
 * @method static LogicalSensorStateEnum STATE_ERROR
 * @method static LogicalSensorStateEnum STATE_UNKNOWN
 */
class LogicalSensorStateEnum extends Enum
{

    private const STATE_OK = 'ok';
    private const STATE_NOTOK = 'not_ok';
    private const STATE_ERROR = 'error';
    private const STATE_UNKNOWN = 'unknown';

}
