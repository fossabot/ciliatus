<?php

namespace Ciliatus\Automation\Http\Controllers;


use Ciliatus\Api\Traits\UsesDefaultCreateMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultDestroyMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultIndexMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultShowMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultUpdateMethodTrait;

class WorkflowExecutionController extends Controller
{

    use UsesDefaultCreateMethodTrait,
        UsesDefaultIndexMethodTrait,
        UsesDefaultShowMethodTrait,
        UsesDefaultUpdateMethodTrait,
        UsesDefaultDestroyMethodTrait;

}
