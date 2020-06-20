<?php

namespace Ciliatus\Core\Http\Controllers;


use Ciliatus\Api\Http\Controllers\ControllerInterface;

abstract class Controller extends \Ciliatus\Api\Http\Controllers\Controller implements ControllerInterface
{

    /**
     * @var string
     */
    protected string $package = 'Core';
}