<?php

namespace Ciliatus\Automation\Http\Controllers;

use Ciliatus\Api\Traits\UsesDefaultCreateMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultDestroyMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultIndexMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultShowMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultUpdateMethodTrait;
use Ciliatus\Automation\Http\Requests\ApplianceErrorRequest;
use Ciliatus\Automation\Http\Requests\ApplianceHealthRequest;
use Ciliatus\Automation\Models\Appliance;
use Ciliatus\Common\Enum\HealthIndicatorStatusEnum;
use Ciliatus\Common\Exceptions\ModelNotFoundException;
use Ciliatus\Common\Factory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class ApplianceController extends Controller
{

    use UsesDefaultCreateMethodTrait,
        UsesDefaultIndexMethodTrait,
        UsesDefaultShowMethodTrait,
        UsesDefaultUpdateMethodTrait,
        UsesDefaultDestroyMethodTrait;

    /**
     * @param ApplianceErrorRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws AuthorizationException
     */
    public function error__update(ApplianceErrorRequest $request, int $id): JsonResponse
    {
        $this->auth();

        $appliance = Factory::findOrFail(Appliance::class, $id);

        return $this->respondWithModel($appliance->error($request->valid()->get('message')));
    }

    /**
     * @param ApplianceHealthRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws AuthorizationException
     */
    public function health__show(ApplianceHealthRequest $request, int $id): JsonResponse
    {
        $this->auth();

        $appliance = Factory::findOrFail(Appliance::class, $id);
        $enum_key = HealthIndicatorStatusEnum::search('type_id');
        $indicator = $appliance->setHealthIndicator(
            $request->valid()->get('state'),
            $request->valid()->get('name'),
            HealthIndicatorStatusEnum::$enum_key(),
            $request->valid()->get('state_text'),
            $request->valid()->get('value')
        );

        return $this->respondWithModel($indicator);
    }

}
