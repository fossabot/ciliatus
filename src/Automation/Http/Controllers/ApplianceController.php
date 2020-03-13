<?php

namespace Ciliatus\Automation\Http\Controllers;

use Ciliatus\Automation\Http\Requests\ApplianceErrorRequest;
use Ciliatus\Automation\Http\Requests\ApplianceHealthRequest;
use Ciliatus\Automation\Models\Appliance;
use Ciliatus\Common\Enum\HealthIndicatorStatusEnum;
use Ciliatus\Common\Exceptions\ModelNotFoundException;
use Ciliatus\Common\Factory;
use Illuminate\Http\JsonResponse;

class ApplianceController extends Controller
{

    /**
     * @param ApplianceErrorRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function error(ApplianceErrorRequest $request, int $id): JsonResponse
    {
        $appliance = Factory::findOrFail(Appliance::class, $id);

        return $this->respondWithModel($appliance->error($request->valid()->get('message')));
    }

    /**
     * @param ApplianceHealthRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function health(ApplianceHealthRequest $request, int $id): JsonResponse
    {
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
